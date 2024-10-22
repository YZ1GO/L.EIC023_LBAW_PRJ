DROP TABLE IF EXISTS User
DROP TABLE IF EXISTS Buyer
DROP TABLE IF EXISTS Seller
DROP TABLE IF EXISTS Game
DROP TABLE IF EXISTS Platform
DROP TABLE IF EXISTS Category
DROP TABLE IF EXISTS Language
DROP TABLE IF EXISTS Player
DROP TABLE IF EXISTS GamePlatform
DROP TABLE IF EXISTS GameCategory
DROP TABLE IF EXISTS GameLanguage
DROP TABLE IF EXISTS GamePlayer
DROP TABLE IF EXISTS Wishlist
DROP TABLE IF EXISTS ShoppingCart
DROP TABLE IF EXISTS PaymentMethod
DROP TABLE IF EXISTS Payment
DROP TABLE IF EXISTS Order
DROP TABLE IF EXISTS Purchase
DROP TABLE IF EXISTS PrePurchase
DROP TABLE IF EXISTS CanceledPurchase
DROP TABLE IF EXISTS DeliveredPurchase
DROP TABLE IF EXISTS Review
DROP TABLE IF EXISTS ReviewLike
DROP TABLE IF EXISTS NotificationWishlist
DROP TABLE IF EXISTS NotificationGame
DROP TABLE IF EXISTS NotificationReview
DROP TABLE IF EXISTS NotificationPurchase
DROP TABLE IF EXISTS Reason
DROP TABLE IF EXISTS Report
DROP TABLE IF EXISTS FAQ
DROP TABLE IF EXISTS About
DROP TABLE IF EXISTS Contacts


CREATE TABLE User (
    id SERIAL PRIMARY KEY,
    username TEXT UNIQUE NOT NULL,
    name TEXT UNIQUE NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL
);

CREATE TABLE Buyer (
    id INT PRIMARY KEY REFERENCES Users(id),
    NIF TEXT UNIQUE,
    birth_date DATE NOT NULL CHECK(birth_date <= CURRENT_DATE),
    coins INT NOT NULL CHECK(coins >= 0) 
);

CREATE TABLE Seller(
    id INT PRIMARY KEY REFERENCES Users(id)
);

CREATE TABLE Game(
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    description TEXT NOT NULL,
    minimum_age INT NOT NULL CHECK(minimum_age >= 0 && minimum_age <= 18),
    price FLOAT NOT NULL CHECK(price >= 0.0),
    owner INT NOT NULL REFERENCES Seller(id)
);

CREATE TABLE CDK(
    id SERIAL PRIMARY KEY,
    code TEXT UNIQUE NOT NULL,
    game INT NOT NULL REFERENCES Game(id)
);

CREATE TABLE Platform(
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL
);

CREATE TABLE Category(
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL
);

CREATE TABLE Language(
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL
);

CREATE TABLE Player(
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL
);

CREATE TABLE GamePlatform(
    id SERIAL PRIMARY KEY,
    game INT NOT NULL REFERENCES Game(id),
    platform INT NOT NULL REFERENCES Platform(id),
    CONSTRAINT game_platform_pair_unique UNIQUE (game,platform)
);

CREATE TABLE GameCategory(
    id SERIAL PRIMARY KEY,
    game INT NOT NULL REFERENCES Game(id),
    category INT NOT NULL REFERENCES Category(id),
    CONSTRAINT game_category_pair_unique UNIQUE (game,category)
);

CREATE TABLE GameLanguage(
    id SERIAL PRIMARY KEY,
    game INT NOT NULL REFERENCES Game(id),
    language INT NOT NULL REFERENCES Language(id),
    CONSTRAINT game_language_pair_unique UNIQUE (game,language)
);

CREATE TABLE GamePlayer(
    id SERIAL PRIMARY KEY,
    game INT NOT NULL REFERENCES Game(id),
    player INT NOT NULL REFERENCES Player(id),
    CONSTRAINT game_player_pair_unique UNIQUE (game,player)
);

CREATE TABLE Media(
    id SERIAL PRIMARY KEY,
    path TEXT NOT NULL,
    game INT NOT NULL REFERENCES Game(id)
)

CREATE TABLE Wishlist(
    id SERIAL PRIMARY KEY,
    buyer INT NOT NULL REFERENCES Buyer(id),
    game INT NOT NULL REFERENCES Game(id),
    CONSTRAINT buyer_game_pair_unique UNIQUE (buyer, game)
);

CREATE TABLE ShoppingCart(
    id SERIAL PRIMARY KEY,
    buyer INT NOT NULL REFERENCES Buyer(id),
    game INT NOT NULL REFERENCES Game(id),
    CONSTRAINT buyer_game_pair_unique UNIQUE (buyer, game)
);

CREATE TABLE PaymentMethod(
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL
);

CREATE TABLE Payment(
    id SERIAL PRIMARY KEY,
    method INT NOT NULL REFERENCES PaymentMethod,
    value FLOAT NOT NULL CHECK(value >= 0.0)
);

CREATE TABLE Order(
    id SERIAL PRIMARY KEY,
    buyer INT NOT NULL REFERENCES Buyer(id),
    payment INT NOT NULL UNIQUE REFERENCES Payment(id),
    time TIMESTAMP NOT NULL CHECK (time <= CURRENT_TIMESTAMP) DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT buyer_time_pair_unique UNIQUE (buyer, time)
);

CREATE TABLE Purchase(
    id SERIAL PRIMARY KEY,
    value FLOAT NOT NULL CHECK(value >= 0.0),
    order INT NOT NULL REFERENCES Order(id)
);

CREATE TABLE PrePurchase(
    id INT PRIMARY KEY REFERENCES Purchase(id),
    game INT NOT NULL REFERENCES Game(id)
);

CREATE TABLE CanceledPurchase(
    id INT PRIMARY KEY REFERENCES Purchase(id),
    game INT NOT NULL REFERENCES Game(id)
);

CREATE TABLE DeliveredPurchase(
   id INT PRIMARY KEY REFERENCES Purchase(id),
   cdk INT NOT NULL REFERENCES CDK(id)
);

CREATE TABLE Review(
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    positive BOOLEAN NOT NULL,
    author INT NOT NULL REFERENCES Buyer(id),
    game INT NOT NULL REFERENCES Game(id),
    CONSTRAINT author_game_pair_unique UNIQUE (author, game)
);

CREATE TABLE ReviewLike(
    id SERIAL PRIMARY KEY,
    review INT NOT NULL REFERENCES Review(id),
    author INT NOT NULL REFERENCES Buyer(id),
    CONSTRAINT review_author_pair_unique UNIQUE (review, author)
);

CREATE TABLE NotificationWishlist(
    id SERIAL PRIMARY KEY,
    wishlist INT NOT NULL REFERENCES Wishlist(id),
    title TEXT NOT NULL,
    description TEXT NOT NULL
);

CREATE TABLE NotificationGame(
    id SERIAL PRIMARY KEY,
    game INT NOT NULL REFERENCES Game(id),
    title TEXT NOT NULL,
    description TEXT NOT NULL
);

CREATE TABLE NotificationPurchase(
    id SERIAL PRIMARY KEY,
    purchase INT NOT NULL REFERENCES Purchase(id),
    title TEXT NOT NULL,
    description TEXT NOT NULL
);

CREATE TABLE NotificationReview(
    id SERIAL PRIMARY KEY,
    review INT NOT NULL REFERENCES Review(id),
    title TEXT NOT NULL,
    description TEXT NOT NULL
);

CREATE TABLE Reason(
    id SERIAL PRIMARY KEY,
    description NOT NULL
);

CREATE TABLE Report(
    id SERIAL PRIMARY KEY,
    buyer INT NOT NULL REFERENCES Buyer(id),
    review INT NOT NULL REFERENCES Review(id),
    reason INT REFERENCES Reason(id),
    description TEXT
    CONSTRAINT reason_or_description_not_null CHECK (reason IS NOT NULL OR description IS NOT NULL)
);

CREATE TABLE FAQ(
    id SERIAL PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL
);

CREATE TABLE About(
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL
);

CREATE TABLE Contacts(
    id SERIAL PRIMARY KEY,
    contact TEXT NOT NULL
);

/*
Lista de sugestão de mudanças:

Mais importantes:

1. Separar purchase em várias tabelas de acordo com o status. Uma purchase está associada a um cdk, no entanto para o caso em que o
status é pre-purchase ou cancelled por o artigo não estar em stock, não é possível associar um cdk à purchase porque não há nenhum em stock.
(Implementei já uma solução para isto neste script).

2.Criar uma classe de associação para associar as notificações aos users que as recebem. Supondo que um user tem uma aba de notificações,
como é que vamos buscar as notificações que devem ser carregadas nessa página se não associarmos as notificações aos users a quem se 
destinam. Para isso, acho que teríamos que criar uma classe de notificações mais geral que fosse uma generalização das mais especificas
e depois criar uma tabela que associasse o id das notificações dessa tabela geral com o id dos users.
(Não implementado neste script ainda).

Menos importantes:

1. NIF é irrelevante, devíamos guardar nesse campo dados de cartão de crédito/débito a ser usado como predefinição em pagamentos(
por exemplo, guardar o número do cartão e os digitos de segurança nesse campo, mas podíamos ter uma tabela com dados bancários que mapeasse
dados de cartão e users e depois até reformular a lógoca de pagamento à volta disso). Não é muito importante, porque os pagamentos não
são reais.

*/



