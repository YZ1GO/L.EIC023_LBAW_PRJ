# ER: Requirements Specification Component

**Project Vision**

The vision for NAME is to establish a leading online marketplace for gamers, specifically focusing on the sale of Content Distribution Keys (CDKs). By providing a secure, efficient, and user-friendly platform, NAME aims to become the go-to destination for gamers seeking affordable game keys, fostering a community that values both accessibility and quality in digital gaming.

## A1: Project Name

**Goals, Business Context, and Environment**

The NAME project seeks to create a web-based platform for gamers to purchase CDKs for various video games. Positioned in the competitive landscape of online gaming marketplaces, NAME aims to deliver a seamless user experience, competitive pricing, and a vast selection of games, catering to both casual and dedicated gamers.

**Motivation**

The increasing demand for digital game distribution presents a significant opportunity for a dedicated platform focused on CDKs. NAME addresses this demand by offering affordable game keys while ensuring a secure and user-friendly purchasing process, appealing to a broad audience of gamers.

**Main Features**

The platform includes features such as advanced search capabilities and product filtering, enabling users to find specific games based on genre or platform. Users can create wishlists to save their favorite games and leave reviews and ratings to share their experiences with others. The shopping cart allows for easy management of purchases, and the streamlined checkout process supports multiple payment options. Additionally, users can track their purchase history and receive notifications about order status, promotions, and wishlist item availability.

**User Profiles**

NAME accommodates four distinct user profiles. *Anonymous Users* can browse products without registration, allowing them to explore the marketplace before deciding to create an account. *Buyers* are registered users who can browse products, purchase CDKs, and engage with the platform through wishlists and reviews. *Administrators* have full control over the platform, managing product listings, user accounts, and overseeing order processing to ensure smooth operations; however, they cannot make purchases. *Sellers* are users who list CDKs for sale and manage their product information and pricing, but they also cannot buy products on the platform. Together, these user profiles create a dynamic marketplace that fosters interaction and supports the gaming community.

---


## A2: Actors and User stories

The following artifact contains the specifications about the actors and their user stories, acting as a guide and simple documentation for these project's requirements.


### 1. Actors
![Actor Diagram](actor_diagram.png)
**Figure 1:** Actor Diagram

| **Actor**            | **Description**                                                                                     |
|----------------------|-----------------------------------------------------------------------------------------------------|
| Anonymous User        | Users who can browse products without registration, allowing them to explore the marketplace, but can choose to authenticate when they wish so.        |
| User                 | Generic user that can access al publicly available information such as listed CDKs.                                      |
| Authenticated User   | Registered users with access to additional features of the platform.                                 |
| Buyer                | Registered users who can browse products, purchase CDKs, and create wishlists and engage with reviews.      |
| Seller               | Users who list CDKs for sale and manage their product information and pricing. Cannot buy products.  |
| Administrator        | Users with full control over the platform, managing product listings, user accounts, and overseeing order processing. Cannot make purchases. |

**Table 1:** Actors and their descriptions

### 2. User Stories

We have defined the following user stories to facilitate the workflow and clarify how the requirements will be implemented in our system.

#### 2.1. Anonymous User

| **Identifier** | **Name**                              | **Priority** | **Description**                                                                                                                                         |
|----------------|---------------------------------------|--------------|---------------------------------------------------------------------------------------------------------------------------------------------------------|
| US1            | Browse Marketplace                    | High         | As an Anonymous User, I want to browse the marketplace and view the product list and categories, so that I can explore available CDKs.                   |
| US2            | View Product Details                  | High         | As an Anonymous User, I want to view detailed information about a CDK, including reviews, so that I can decide if I want to purchase it.                 |
| US3            | Register Account                      | High         | As an Anonymous User, I want to register an account, so that I can access additional features.                                         |
| US4            | Sign In Account                      | High         | As an Anonymous User, I want to sign in to my account, so that I can access additional features.                                         |


#### 2.2. Authenticated User

| **Identifier** | **Name**                              | **Priority** | **Description**                                                                                                                                         |
|----------------|---------------------------------------|--------------|---------------------------------------------------------------------------------------------------------------------------------------------------------|
| US5           | Update Profile Information            | Low          | As an Authenticated User, I want to update my profile information, so that my account details are current.                                                |
| US6           | Change Password                       | Medium          | As an Authenticated User, I want to change my password, so that I can maintain the security of my account.                                                |
| US7           | View Activity History                 | Low          | As an Authenticated User, I want to view my activity history, so that I can keep track of my interactions on the platform.                                |
| US8           | Access Public Information             | High         | As a Authenticated User, I want to access all publicly available information, so that I can make informed decisions about CDKs.                                          |
| US9           | Contact Customer Support              | Medium       | As a Authenticated User, I want to be able to contact customer support, so that I can get help with any issues or questions I have.                                                |


#### 2.3. Buyer

| **Identifier** | **Name**                              | **Priority** | **Description**                                                                                                                                         |
|----------------|---------------------------------------|--------------|---------------------------------------------------------------------------------------------------------------------------------------------------------|
| US10            | Search CDKs by Genre/Platform         | High         | As a Buyer, I want to search for CDKs by genre or platform, so that I can quickly find the games I am interested in.                                      |
| US11            | Add to Wishlist                       | Medium       | As a Buyer, I want to add CDKs to my wishlist, so that I can save them for future purchases.                                                             |
| US12            | Leave Reviews and Ratings             | Medium       | As a Buyer, I want to leave reviews and ratings for CDKs I have purchased, so that I can share my experience with other users.                            |
| US13            | Manage Shopping Cart                  | High         | As a Buyer, I want to manage my shopping cart, so that I can easily add or remove items before checkout.                                                  |
| US14            | Multiple Payment Options              | High         | As a Buyer, I want to complete my purchase using multiple payment methods, so that I can choose the most convenient method for me.                        |
| US15            | Track Purchase History                | Medium       | As a Buyer, I want to track my purchase history, so that I can review my past orders.                                                                     |
| US16           | Receive Order and Price Notifications | Medium       | As a Buyer, I want to receive notifications about order status, promotions, and price changes on products in my cart or wishlist.                         |
| US17           | Receive Payment and Order Notifications | Medium     | As a Buyer, I want to receive notifications about payment approvals and changes in order status, so that I am updated on my purchase progress.            |
| US18           | Manage Wishlist                       | Medium       | As a Buyer, I want to manage my wishlist, so that I can keep track of desired CDKs and purchase them in the future.                                       |
| US19           | Review Purchased Product              | Medium       | As a Buyer, I want to review products I have purchased, so that I can share my feedback with other users.                                                 |
| US20           | Edit or Remove Review                 | Medium       | As a Buyer, I want to edit or remove my reviews, so that I can update or delete feedback as necessary.                                             |
| US21           | Cancel Order                          | Medium       | As a Buyer, I want to cancel my order, so that I can manage my purchases effectively if I change my mind.                                                 |
| US22           | Report Inappropriate Reviews          | Medium       | As a Buyer, I want to report inappropriate reviews, so that I can help maintain a respectful and constructive community.                                   |

#### 2.4. Seller

| **Identifier** | **Name**                              | **Priority** | **Description**                                                                                                                                         |
|----------------|---------------------------------------|--------------|---------------------------------------------------------------------------------------------------------------------------------------------------------|
| US23           | List CDKs for Sale                    | High         | As a Seller, I want to list CDKs for sale, so that I can reach potential buyers.                                                                          |
| US24           | Manage Product Information and Pricing | Medium       | As a Seller, I want to manage my product information and pricing, so that I can keep my listings up-to-date and competitive.                              |
| US25           | View Sales History                    | Medium       | As a Seller, I want to view my sales history, so that I can track my performance and earnings.                                                            |
| US26           | Receive Seller Notifications          | Medium       | As a Seller, I want to receive notifications about sales and buyer reviews, so that I can stay informed about my transactions and feedback.               |
| US27           | Manage Product Listings               | High         | As an Administrator, I want to manage product listings and stock, so that the marketplace has accurate and relevant information.                         |

#### 2.5. Administrator

| **Identifier** | **Name**                              | **Priority** | **Description**                                                                                                                                         |
|----------------|---------------------------------------|--------------|---------------------------------------------------------------------------------------------------------------------------------------------------------|
| US28           | Manage User Accounts                  | High         | As an Administrator, I want to manage user accounts, so that I can maintain a secure and orderly platform.                                                |
| US29           | Oversee Order Processing              | High         | As an Administrator, I want to oversee order processing and manage order status, so that I can ensure smooth operations and address any issues promptly.  |
| US30           | Manage Product Discounts              | Medium       | As an Administrator, I want to manage product discounts, so that I can offer promotions and sales on CDKs.                                                |


### 3. Supplementary Requirements

> Section including business rules, technical requirements, and restrictions.  
> For each subsection, a table containing identifiers, names, and descriptions for each requirement.

#### 3.1. Business rules

#### 3.2. Technical requirements

#### 3.3. Restrictions


---


## A3: Information Architecture

> Brief presentation of the artifact goals.


### 1. Sitemap

> Sitemap presenting the overall structure of the web application.  
> Each page must be identified in the sitemap.  
> Multiple instances of the same page (e.g. student profile in SIGARRA) are presented as page stacks.


### 2. Wireframes

> Wireframes for, at least, two main pages of the web application.
> Do not include trivial use cases (e.g. about page, contacts).


#### UIxx: Page Name

#### UIxx: Page Name


---


## Revision history

Changes made to the first submission:
1. Item 1
1. ...

***
GROUPYYgg, DD/MM/20YY

* Group member 1 name, email (Editor)
* Group member 2 name, email
* ...