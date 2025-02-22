<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;


class PurchaseHistoryController extends Controller
{
    public function orderHistory(Request $request)
    {
        if (!auth_user() || !auth_user()->buyer) {
            return redirect()->route('login');
        }

        $buyerId = auth_user()->buyer->id;

        return view('pages.purchase-history', $this->getOrderHistory($request, $buyerId));
    }

    public function adminOrderHistory(Request $request, $buyerId)
    {
        if (!auth_user() || !is_admin()) {
            return redirect()->route('login');
        }

        $buyer = User::findOrFail($buyerId);
        $orderHistoryData = $this->getOrderHistory($request, $buyerId);
    
        return view('pages.purchase-history', array_merge($orderHistoryData, [
            'buyerUsername' => $buyer->username,
            'buyerId' => $buyer->id
        ]));
    }

    private function getOrderHistory(Request $request, $buyerId)
    {
        $sortBy = $request->get('sortBy', 'time');
        $direction = $request->get('direction', 'desc');
        $filter = $request->input('filter', 'all');
    
        $ordersQuery = Order::with([
            'getPayment.getPaymentMethod',
            'getPurchases.getDeliveredPurchase.getCDK.getGame',
            'getPurchases.getPrePurchase.getGame',
            'getPurchases.getCanceledPurchase.getGame'
        ])
        ->where('buyer', $buyerId)
        ->whereHas('getPurchases', function ($query) {
            $query->whereHas('getDeliveredPurchase')
                  ->orWhereHas('getPrePurchase')
                  ->orWhereHas('getCanceledPurchase');
        });
    
        if ($filter === 'Completed') {
            $ordersQuery->whereDoesntHave('getPurchases.getPrePurchase')
                       ->whereDoesntHave('getPurchases.getCanceledPurchase');
        } elseif ($filter === 'ItemPending') {
            $ordersQuery->whereHas('getPurchases.getPrePurchase');
        } elseif ($filter === 'Canceled') {
            $ordersQuery->whereHas('getPurchases.getCanceledPurchase')
                       ->whereDoesntHave('getPurchases.getPrePurchase')
                       ->whereDoesntHave('getPurchases.getDeliveredPurchase');
        } elseif ($filter === 'PartiallyCompleted') {
            $ordersQuery->whereHas('getPurchases.getCanceledPurchase')
                       ->whereHas('getPurchases.getDeliveredPurchase')
                       ->whereDoesntHave('getPurchases.getPrePurchase');
        }
    
        if ($sortBy === 'totalPrice') {
            $ordersQuery->select('orders.*', 'payment.value as total_cost')
            ->join('payment', 'orders.payment', '=', 'payment.id')
            ->orderBy('payment.value', $direction);
        } else {
            $ordersQuery->orderBy('time', $direction);
        }
    
        $orders = $ordersQuery->paginate(5)->appends([
            'sortBy' => $sortBy,
            'direction' => $direction,
            'filter' => $filter,
        ]);
    
        $orderHistory = $orders->map(function ($order) {
            $paymentMethodName = $order->getPayment->getPaymentMethod->name ?? 'Unknown';
            $paymentMethodImage= $order->getPayment->getPaymentMethod->image_path ?? '';
    
            // **Delivered Purchases Grouped by Game**
            $deliveredPurchasesGrouped = Purchase::where('order_', $order->id)
                ->whereHas('getDeliveredPurchase')
                ->get()
                ->groupBy(function ($purchase) {
                    return $purchase->getDeliveredPurchase->getCDK->getGame->id;
                })
                ->map(function ($group) {
                    $game = $group->first()->getDeliveredPurchase->getCDK->getGame;
                    return [
                        'game_id' => $game->id,
                        'game_name' => $game->name ?? 'Unknown Game',
                        'quantity' => $group->count(),
                        'unit_price' => $group->first()->value ?? 0,
                        'delivery_status' => 'Delivered',
                    ];
                });
    
            // **Pre Purchases Grouped by Game**
            $prePurchasesGrouped = Purchase::where('order_', $order->id)
                ->whereHas('getPrePurchase')
                ->get()
                ->groupBy(function ($purchase) {
                    return $purchase->getPrePurchase->getGame->id;
                })
                ->map(function ($group) {
                    $game = $group->first()->getPrePurchase->getGame;
                    $deliveryStatus = ($game->getReleaseDate() === 'Not realeased yet') ? 'Unreleased' : 'Sold Out';
                    return [
                        'game_id' => $game->id,
                        'game_name' => $game->name ?? 'Unknown Game',
                        'quantity' => $group->count(),
                        'unit_price' => $group->first()->value ?? 0,
                        'delivery_status' => $deliveryStatus,
                    ];
                });

                $canceledPurchasesGrouped = Purchase::where('order_', $order->id)
                ->whereHas('getCanceledPurchase')
                ->get()
                ->groupBy(function ($purchase) {
                    return $purchase->getCanceledPurchase->getGame->id;
                })
                ->map(function ($group) {
                    $game = $group->first()->getCanceledPurchase->getGame;
                    return [
                        'game_id' => $game->id,
                        'game_name' => $game->name ?? 'Unknown Game',
                        'quantity' => $group->count(),
                        'unit_price' => $group->first()->value ?? 0,
                        'delivery_status' => 'Canceled',
                    ];
                });    
    
            // **Merge Delivered and Pending Purchases**
            $games = $deliveredPurchasesGrouped->concat($prePurchasesGrouped)->concat($canceledPurchasesGrouped);
    
            $totalPrice = $order->getPayment->value;
            $formattedTime = $this->formatOrderTime($order->time);
    
            // **Determine Order Status**
            if($prePurchasesGrouped->count() > 0){
                $status = 'Item Pending';
            }
            elseif($canceledPurchasesGrouped->count() > 0){
                if($deliveredPurchasesGrouped->count() == 0){
                    $status = 'Canceled';
                }
                else{
                    $status = 'Partially Completed';
                }
            }
            else{
                $status = 'Completed';
            }
    
            return [
                'order' => $order,
                'payment' => $paymentMethodName,
                'paymentImage' => $paymentMethodImage,
                'games' => $games,
                'formattedTime' => $formattedTime,
                'totalPrice' => $totalPrice,
                'status' => $status,
                'coinsUsed' => $order->coins,
            ];
        });    

        return ['orderHistory' => $orderHistory, 'orders' => $orders];
    }


    public function fetchOrderDetails($id)
    {
        // Ensure the user is authenticated and is a buyer
        if (!auth_user() || !auth_user()->buyer) {
            return redirect()->route('login');
        }

        $buyerId = auth_user()->buyer->id;

        // Fetch the specific order with necessary relationships
        $order = Order::with([
            'getPayment.getPaymentMethod',
            'getPurchases.getDeliveredPurchase.getCDK.getGame',
            'getPurchases.getPrePurchase.getGame'
        ])
        ->where('id', $id)
        ->where('buyer', $buyerId)
        ->firstOrFail();

        // Group Delivered Purchases by Game
        $deliveredPurchasesGrouped = Purchase::where('order_', $order->id)
            ->whereHas('getDeliveredPurchase')
            ->get()
            ->groupBy(function ($purchase) {
                return $purchase->getDeliveredPurchase->getCDK->getGame->id;
            })
            ->map(function ($group) {
                $game = $group->first()->getDeliveredPurchase->getCDK->getGame;
                return [
                    'game_id' => $game->id,
                    'game_name' => $game->name ?? 'Unknown Game',
                    'game_image' => $game->getThumbnailSmallPath() ?? null,
                    'base_price' => $game->price ?? 0,
                    'purchase_count' => $group->count(),
                    'cdk_codes' => $group->map(function ($purchase) {
                        return $purchase->getDeliveredPurchase->getCDK->code ?? 'No CDK';
                    })->toArray(),
                ];
            });

        // Group Pre-Purchases by Game
        $prePurchasesGrouped = Purchase::where('order_', $order->id)
            ->whereHas('getPrePurchase')
            ->get()
            ->groupBy(function ($purchase) {
                return $purchase->getPrePurchase->getGame->id;
            })
            ->map(function ($group) {
                $game = $group->first()->getPrePurchase->getGame;
                return [
                    'game_id' => $game->id,
                    'game_name' => $game->name ?? 'Unknown Game',
                    'game_image' => $game->thumbnail_small_path ?? null,
                    'base_price' => $game->price ?? 0,
                    'purchase_count' => $group->count(),
                    'purchase_ids' => $group->pluck('id')->toArray(),
                ];
            });

        // Calculate Total Price
        $totalPrice = $deliveredPurchasesGrouped->sum(function ($item) {
            return $item['base_price'] * $item['purchase_count'];
        }) + $prePurchasesGrouped->sum(function ($item) {
            return $item['base_price'] * $item['purchase_count'];
        });

        // Format Order Time (Implement this method as needed)
        $formattedTime = $this->formatOrderTime($order->time);

        // Pass data to the view
        return view('pages.orderDetails', [
            'order' => $order,
            'deliveredPurchases' => $deliveredPurchasesGrouped,
            'prePurchases' => $prePurchasesGrouped,
            'totalPrice' => $totalPrice,
            'formattedTime' => $formattedTime,
        ]);
    }


    public function purchaseDetails($id)
    {
        $purchase = DB::table('deliveredpurchase')
            ->join('cdk', 'deliveredpurchase.cdk', '=', 'cdk.id')
            ->join('purchase', 'deliveredpurchase.id', '=', 'purchase.id')
            ->join('orders', 'purchase.order_', '=', 'orders.id')
            ->join('users', 'orders.buyer', '=', 'users.id')
            ->join('payment', 'orders.payment', '=', 'payment.id')
            ->join('paymentmethod', 'payment.method', '=', 'paymentmethod.id')
            ->where('deliveredpurchase.id', $id)
            ->select('deliveredpurchase.*', 'cdk.code as cdk_code', 'purchase.value', 'orders.time', 'users.name as buyer_name', 'users.email as buyer_email', 'paymentmethod.name as payment_method_name', 'paymentmethod.image_path as payment_method_image')
            ->first();

        if (!$purchase) {
            return redirect()->back()->with('error', 'Purchase not found.');
        }

        $game = DB::table('game')
        ->join('cdk', 'game.id', '=', 'cdk.game')
        ->where('cdk.id', $purchase->cdk)
        ->select('game.*')
        ->first();

        $seller = null;

        if (is_admin()) {

            $seller = DB::table('users')
                ->join('game', 'users.id', '=', 'game.owner')
                ->where('game.id', $game->id)
                ->select('users.name as seller_name', 'users.email as seller_email')
                ->first();
        }

        return view('pages.purchase-details', compact('purchase', 'game', 'seller'));
    }

    private function formatOrderTime($orderTime)
    {
        $orderDateTime = new \DateTime($orderTime);
        $currentDate = new \DateTime();

        if ($orderDateTime->format('Y-m-d') === $currentDate->format('Y-m-d')) {
            return 'Today at ' . $orderDateTime->format('H:i');
        }

        $yesterday = (clone $currentDate)->modify('-1 day');
        if ($orderDateTime->format('Y-m-d') === $yesterday->format('Y-m-d')) {
            return 'Yesterday at ' . $orderDateTime->format('H:i');
        }

        return $orderDateTime->format('Y-m-d H:i');
    }
}



