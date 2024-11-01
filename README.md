# EBD: Database Specification Component

**Project Vision**

**STEAL!** is an online marketplace focused on selling Content Distribution Keys (CDKs). It offers a secure, user-friendly platform for gamers seeking affordable game keys, aiming to become the top destination for accessible, high-quality digital gaming.

> **"SO AFFORDABLE IT'S LIKE STEALING!"** 

----

## A4: Conceptual Data Model

The goal of the class diagram for STEAL! is to visually represent the core components and structure of the platform, defining the key entities, attributes, and behaviors necessary to manage the buying, selling, and distribution of game CDKs.

### 1. Class diagram

> The following artifact is a UML class diagram representing the conceptual model of the STEAL! platform.

![conceptual_model](assets/diagrams/Conceptual_Model.png)

**Figure 1:** STEAL! conceptual data model in UML.

### 2. Additional Business Rules
 
- BR01 - A seller is unable to purchase games on the platform.
- BR02 - A buyer is unable to purchase games that have a higher age rating than their own.
- BR03 - A buyer may only like a particular review once. Each combination of a buyer and a review must be unique within the review like system.

---


## A5: Relational Schema, validation and schema refinement

> This artifact outlines the STEAL! platform's relational schema, detailing key relations, attributes, data types, and integrity constraints to ensure data consistency and accuracy in the database design.

### 1. Relational Schema

> The Relational Schema specifies the relations, attributes, domains, and integrity constraints like UNIQUE, DEFAULT, NOT NULL, and CHECK. Each relation is represented in a compact notation for easy reference. 

| Relation reference | Relation Compact Notation                        |
| ------------------ | ------------------------------------------------ |
| R01                | administrator(<ins>id</ins>, username **UK** **NN**, name **NN**, email **UK** **NN**, password **NN**) |
| R02                | user(<ins>id</ins>, username **UK** **NN**, name **NN**, email **UK** **NN**, password **NN**, is_active **NN** **DF** TRUE) |
| R03                | buyer(<ins>id_user</ins> -> user **NN**, NIF **UK**, birth_date **NN** **CK** birth_date <= Today, coins **NN** **CK** coins >= 0) |
| R04                | seller(<ins>id_user</ins> -> user **NN**) |
| R05                | wishlist(<ins>id</ins>, <ins>id_buyer</ins> → buyer **NN**, id_game -> game) |
| R06                | shopping_cart(<ins>id</ins>, <ins>id_buyer</ins> → buyer **NN**, id_game -> game , quantity **NN** **CK** quantity >= 0) |
| R07                | order(<ins>id</ins>, <ins>id_buyer</ins> -> buyer **NN**, <ins>id_payment</ins> -> payment **NN**, date **NN**) |
| R08                | payment(<ins>id</ins>, <ins>id_method</ins> -> payment_method **NN**, value **NN** **CK** value > 0.0) |
| R09                | payment_method(<ins>id</ins>, method **NN**) |
| R10                | notification_wishlist(<ins>id</ins>, id_wishlist -> wishlist **NN**, title **NN**, description **NN**) |
| R11                | notification_game(<ins>id</ins>, id_game -> game **NN**, title **NN**, description **NN**) |
| R12                | notification_purchase(<ins>id</ins>, id_purchase -> purchase **NN**, title **NN**, description **NN**) |
| R13                | notification_review(<ins>id</ins>, id_review -> review **NN**, title **NN**, description **NN**) |
| R14                | review(<ins>id</ins>, title **NN**, description **NN**, recommend **NN**, id_author -> buyer **NN**, id_game -> game**NN**) |
| R15                | review_like(<ins>id</ins>, id_review -> review **NN**, id_author -> buyer **NN**, (id_review, id_author) **UK**) |
| R16                | report(<ins>id</ins>, description **NN**, id_buyer -> buyer **NN**, id_reason -> reason **NN**, id_review -> review **NN**) |
| R17                | reason(<ins>id</ins>, reason **NN**) |
| R18                | game(<ins>id</ins>, name **NN**, description **NN**, minimum_age **NN** **CK** minimum_age > 0 AND minimum_age <= 18, price **NN** **CK** price > 0.0, overall_rating **NN** **CK** overall_rating >= 0 AND overall_rating <= 100, is_active **NN** **DF** TRUE, id_owner -> seller **NN**) |
| R19                | game_platform(<ins>id</ins>, id_game -> game **NN**, id_platform -> platform **NN**, (id_game, id_platform) **UK**) |
| R20                | game_category(<ins>id</ins>, id_game -> game **NN**, id_category -> category **NN**, (id_game, id_category) **UK**) |
| R21                | game_language(<ins>id</ins>, id_game -> game **NN**, id_language -> language **NN**, (id_game, id_language) **UK**) |
| R22                | game_player(<ins>id</ins>, id_game -> game **NN**, id_player -> player **NN**, (id_game, id_player) **UK**) |
| R23                | cdk(<ins>id</ins>, code **UK** **NN**, id_game -> game **NN**) |
| R24                | stock(<ins>id</ins>, quantity **NN** **CK** quantity >= 0, <ins>id_game</ins> -> game **NN**) |
| R25                | platform(<ins>id</ins>, platform **NN**) |
| R26                | category(<ins>id</ins>, category **NN**) |
| R27                | language(<ins>id</ins>, language **NN**) |
| R28                | player(<ins>id</ins>, player **NN**) |
| R29                | media(<ins>id</ins>, path **NN**, id_game -> game **NN**) |
| R30                | purchase(<ins>id</ins>, value **NN** **CK** value > 0.0, status **NN** **CK** status **IN** Status, id_order -> order **NN**, id_game -> game **NN**, id_cdk -> cdk **UK**) |
| R31                | faq(<ins>id</ins>, question **NN**, answer **NN**) |
| R32                | about(<ins>id</ins>, about **NN**) |
| R33                | contact(<ins>id</ins>, contact **NN**) |

Legend: 
- UK = UNIQUE KEY
- NN = NOT NULL
- CK = CHECK
- DF = DEFAULT

### 2. Domains

> Specification of additional domains:  

| Domain Name | Domain Specification           |
| ----------- | ------------------------------ |
| Today	      | DATE DEFAULT CURRENT_DATE      |
| Status      | ENUM ('Pre_Purchased', 'Cancelled', 'Delivered') |

### 3. Schema validation

> To validate the Relational Schema obtained from the Conceptual Model, all functional dependencies are identified and the normalization of all relation schemas is accomplished. Should it be necessary, in case the scheme is not in the Boyce–Codd Normal Form (BCNF), the relational schema is refined using normalization.  

| **TABLE R01** | administrator |
| - | - |
| **Keys** | { id }, { username }, { email } |
| **Functional Dependencies:** | |
| FD0101 | id → {username, name, email, password} |
| FD0102 | username → {id, name, email, password} |
| FD0103 | email → {id, username, name, password} |
| **NORMAL FORM** | BCNF |

| **TABLE R02** | user |
| - | - |
| **Keys** | { id }, { username }, { email } |
| **Functional Dependencies:** | |
| FD0201 | id → {username, name, email, password, is_active} |
| FD0202 | username → {id, name, email, password, is_active} |
| FD0203 | email → {id, username, name, password, is_active} |
| **NORMAL FORM** | BCNF |

| **TABLE R03** | buyer |
| - | - |
| **Keys** | { id_user }, { NIF } |
| **Functional Dependencies:** | |
| FD0301 | id_user → {NIF, birth_date, coins} |
| FD0302 | NIF → {id_user, birth_date, coins} |
| **NORMAL FORM** | BCNF |

| **TABLE R04** | seller |
| - | - |
| **Keys** | { id_user } |
| **Functional Dependencies:** | *none* |
| **NORMAL FORM** | BCNF |

| **TABLE R05** | wishlist |
| - | - |
| **Keys** | { id }, { id_buyer, id_game } |
| **Functional Dependencies:** | |
| FD0501 | id → {id_buyer, id_game} |
| FD0502 | { id_buyer, id_game } → {id} |
| **NORMAL FORM** | BCNF |

| **TABLE R06** | shopping_cart |
| - | - |
| **Keys** | { id }, { id_buyer, id_game } |
| **Functional Dependencies:** | |
| FD0601 | id → {id_buyer, id_game, quantity} |
| FD0602 | { id_buyer, id_game } → {id, quantity} |
| **NORMAL FORM** | BCNF |

| **TABLE R07** | order |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD0701 | id → {id_buyer, id_payment, date} |
| **NORMAL FORM** | BCNF |

| **TABLE R08** | payment |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD0801 | id → {id_method, value} |
| **NORMAL FORM** | BCNF |

| **TABLE R09** | payment_method |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD0901 | id → {method} |
| **NORMAL FORM** | BCNF |

| **TABLE R10** | notification_wishlist |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD1001 | id → {id_wishlist, title, description} |
| **NORMAL FORM** | BCNF |

| **TABLE R11** | notification_game |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD1101 | id → {id_game, title, description} |
| **NORMAL FORM** | BCNF |

| **TABLE R12** | notification_purchase |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD1201 | id → {id_purchase, title, description} |
| **NORMAL FORM** | BCNF |

| **TABLE R13** | notification_review |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD1301 | id → {id_review, title, description} |
| **NORMAL FORM** | BCNF |

| **TABLE R14** | review |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD1401 | id → {title, description, recommend, id_author, id_game} |
| **NORMAL FORM** | BCNF |

| **TABLE R15** | review_like |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD1501 | id → {id_review, id_author} |
| **NORMAL FORM** | BCNF |

| **TABLE R16** | report |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD1601 | id → {description, id_buyer, id_reason, id_review} |
| **NORMAL FORM** | BCNF |

| **TABLE R17** | reason |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD1701 | id → {reason} |
| **NORMAL FORM** | BCNF |

| **TABLE R18** | game |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD1801 | id → {name, description, minimum_age, price, overall_rating, is_active, id_owner} |
| **NORMAL FORM** | BCNF |

| **TABLE R19** | game_platform |
| - | - |
| **Keys** | { id }, { id_game, id_platform }|
| **Functional Dependencies:** | |
| FD1901 | id → {id_game, id_platform} |
| FD1902 | {id_game, id_platform} → {id} |
| **NORMAL FORM** | BCNF |

| **TABLE R20** | game_category |
| - | - |
| **Keys** | { id }, { id_game, id_category } |
| **Functional Dependencies:** | |
| FD2001 | id → {id_game, id_category} |
| FD2002 | {id_game, id_category} → {id} |
| **NORMAL FORM** | BCNF |

| **TABLE R21** | game_language |
| - | - |
| **Keys** | { id }, { id_game, id_language } |
| **Functional Dependencies:** | |
| FD2101 | id → {id_game, id_language} |
| FD2102 | {id_game, id_language} → {id} |
| **NORMAL FORM** | BCNF |

| **TABLE R22** | game_player |
| - | - |
| **Keys** | { id }, { id_game, id_player } |
| **Functional Dependencies:** | |
| FD2201 | id → {id_game, id_player} |
| FD2202 | {id_game, id_player} → {id} |
| **NORMAL FORM** | BCNF |

| **TABLE R23** | cdk |
| - | - |
| **Keys** | { id }, { code } |
| **Functional Dependencies:** | |
| FD2301 | id → {code, id_game} |
| FD2302 | code → {id, id_game} |
| **NORMAL FORM** | BCNF |

| **TABLE R24** | stock |
| - | - |
| **Keys** | { id }, { id_game } |
| **Functional Dependencies:** | |
| FD2401 | id → {quantity, id_game} |
| FD2402 | id_game → {id, quantity} |
| **NORMAL FORM** | BCNF |

| **TABLE R25** | platform |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD2501 | id → {platform} |
| **NORMAL FORM** | BCNF |

| **TABLE R26** | category |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD2601 | id → {category} |
| **NORMAL FORM** | BCNF |

| **TABLE R27** | language |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD2701 | id → {language} |
| **NORMAL FORM** | BCNF |

| **TABLE R28** | player |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD2801 | id → {player} |
| **NORMAL FORM** | BCNF |

| **TABLE R29** | media |
| - | - |
| **Keys** | { id }, { id_game } |
| **Functional Dependencies:** | |
| FD2901 | id → {path, id_game} |
| FD2902 | id_game → {id, path} |
| **NORMAL FORM** | BCNF |

| **TABLE R30** | purchase |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD3001 | id → {value, status, id_order, id_game, id_cdk} |
| **NORMAL FORM** | BCNF |

| **TABLE R31** | faq |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD3101 | id → {question, answer} |
| **NORMAL FORM** | BCNF |

| **TABLE R32** | about |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD3201 | id → {about} |
| **NORMAL FORM** | BCNF |

| **TABLE R33** | contact |
| - | - |
| **Keys** | { id } |
| **Functional Dependencies:** | |
| FD3301 | id → {contact} |
| **NORMAL FORM** | BCNF |

---


## A6: Indexes, triggers, transactions and database population

This artifact outlines the strategies and mechanisms employed to ensure efficient database operations and data integrity within the platform. Indexes are used to optimize query performance, enabling faster retrieval of data. Triggers are implemented to automate specific actions in response to certain events, ensuring consistency and enforcing business rules. Transactions are utilized to maintain data integrity by grouping a series of operations into a single, atomic unit of work, which either fully completes or fully rolls back in case of an error. Finally, database population techniques are discussed to ensure the initial and ongoing seeding of the database with relevant data, facilitating a robust and scalable environment for the platform.

### 1. Database Workload
 
We carried out an analysis of the anticipated system load on the database, including estimates of the number of tuples (records) for each relation, as understanding the database workload is crucial for optimizing performance and ensuring scalability. The table below outlines the expected order of magnitude for each relation and their estimated growth over time.

| **Relation Reference** | **Relation Name**      | **Order of Magnitude**       | **Estimated Growth**           |
|------------------------|------------------------|------------------------------|--------------------------------|
| R01                    | administrator          | Dozens                       | Rare                           |
| R02                    | user                   | Hundreds of thousands        | Thousands per month            |
| R03                    | buyer                  | Hundreds of thousands        | Thousands per month            |
| R04                    | seller                 | Dozens                       | Dozens per month               |
| R05                    | wishlist               | Hundreds of thousands        | Thousands per month            |
| R06                    | shopping_cart          | Hundreds of thousands        | Thousands per month            |
| R07                    | order                  | Hundreds of thousands        | Thousands per month            |
| R08                    | payment                | Hundreds of thousands        | Thousands per month            |
| R09                    | payment_method         | Dozens                       | Rare                           |
| R10                    | notification_wishlist  | Hundreds of thousands        | Thousands per month            |
| R11                    | notification_game      | Hundreds of thousands        | Thousands per month            |
| R12                    | notification_purchase  | Hundreds of thousands        | Thousands per month            |
| R13                    | notification_review    | Hundreds of thousands        | Thousands per month            |
| R14                    | review                 | Hundreds of thousands        | Thousands per month            |
| R15                    | review_like            | Hundreds of thousands        | Thousands per month            |
| R16                    | report                 | Hundreds                     | Hundreds per month             |
| R17                    | reason                 | Dozens                       | Rare                           |
| R18                    | game                   | Hundreds of thousands        | Thousands per month            |
| R19                    | game_platform          | Dozens                       | Rare                           |
| R20                    | game_category          | Dozens                       | Rare                           |
| R21                    | game_language          | Dozens                       | Rare                           |
| R22                    | game_player            | Single digits                | Rare                           |
| R23                    | cdk                    | Hundreds of thousands        | Thousands per month            |
| R24                    | stock                  | Hundreds                     | Hundreds per month             |
| R25                    | platform               | Dozens                       | Rare                           |
| R26                    | category               | Dozens                       | Rare                           |
| R27                    | language               | Dozens                       | Rare                           |
| R28                    | player                 | Single digits                | Rare                           |
| R29                    | media                  | Hundreds of thousands        | Thousands per month            |
| R30                    | purchase               | Hundreds of thousands        | Thousands per month            |
| R31                    | faq                    | Dozens                       | Rare                           |
| R32                    | about                  | Dozens                       | Rare                           |
| R33                    | contact                | Dozens                       | Rare                           |

### 2. Proposed Indices
 
#### 2.1. Performance Indices
 
The following indices are proposed to improve performance of the identified queries.

| **Index**           | IDX01                                  |
| ------------------- | -------------------------------------- |
| **Relation**        | game                                   |
| **Attribute**       | price                                  |
| **Type**            | B-tree                                 |
| **Cardinality**     | High                                   |
| **Clustering**      | No                                     |
| **Justification**   | Optimizes range queries for filtering games within specific price ranges. |
| `SQL code`          | `CREATE INDEX idx_game_price ON game (price);` |

| **Index**           | IDX02                                  |
| ------------------- | -------------------------------------- |
| **Relation**        | review                                 |
| **Attribute**       | id_game                                |
| **Type**            | B-tree                                 |
| **Cardinality**     | High                                   |
| **Clustering**      | No                                     |
| **Justification**   | Optimizes retrieval of reviews for specific games, enhancing performance of queries filtering on game reviews. |
| `SQL code`          | `CREATE INDEX idx_review_id_game ON review (id_game);` |

| **Index**           | IDX03                                  |
| ------------------- | -------------------------------------- |
| **Relation**        | purchase                               |
| **Attribute**       | id_buyer                               |
| **Type**            | B-tree                                 |
| **Cardinality**     | High                                   |
| **Clustering**      | No                                     |
| **Justification**   | Speeds up queries retrieving purchase history for specific buyers. |
| `SQL code`          | `CREATE INDEX idx_purchase_id_buyer ON purchase (id_buyer);` |

| **Index**           | IDX04                                  |
| ------------------- | -------------------------------------- |
| **Relation**        | shopping_cart                          |
| **Attribute**       | id_buyer                               |
| **Type**            | B-tree                                 |
| **Cardinality**     | Medium                                 |
| **Clustering**      | No                                     |
| **Justification**   | Increases efficiency of operations accessing or updating a buyer's shopping cart data. |
| `SQL code`          | `CREATE INDEX idx_shopping_cart_id_buyer ON shopping_cart (id_buyer);` |

| **Index**           | IDX05                                  |
| ------------------- | -------------------------------------- |
| **Relation**        | cdk                                    |
| **Attribute**       | code                                   |
| **Type**            | Hash                                   |
| **Cardinality**     | High                                   |
| **Clustering**      | No                                     |
| **Justification**   | Enables rapid searches for CDK codes to prevent duplicates and ensure quick validation during purchase. |
| `SQL code`          | `CREATE INDEX idx_cdk_code ON cdk USING hash (code);` |

#### 2.2. Full-text Search Indices 

To improve user experience, it’s crucial for our system to support full-text search (FTS) capabilities within the game relation, specifically targeting attributes like title and description. By implementing PostgreSQL’s GIN index type, we can ensure that users can efficiently search for games using keywords. This setup will involve creating the necessary configurations and indexes to maintain up-to-date search functionality, allowing users to find relevant games quickly.

| **Index**           | IDX01                                  |
|---------------------|---------------------------------------|
| **Relation**        | game                                  |
| **Attribute**       | title, description                    |
| **Type**            | GIN                                   |
| **Clustering**      | No                                    |
| **Justification**   | To provide full-text search features for finding games based on matching titles or descriptions. The GIN index type is chosen for its efficiency in handling FTS queries, particularly when dealing with large datasets and varying text lengths. |
##### SQL Code
```sql
-- Add column to game to store computed ts_vectors.
ALTER TABLE game
ADD COLUMN tsvectors TSVECTOR;

-- Create a function to automatically update ts_vectors.
CREATE FUNCTION game_search_update() RETURNS TRIGGER AS $$
BEGIN
  IF TG_OP = 'INSERT' THEN
    NEW.tsvectors = (
      setweight(to_tsvector('english', NEW.title), 'A') ||
      setweight(to_tsvector('english', NEW.description), 'B')
    );
  END IF;
  IF TG_OP = 'UPDATE' THEN
    IF (NEW.title <> OLD.title OR NEW.description <> OLD.description) THEN
      NEW.tsvectors = (
        setweight(to_tsvector('english', NEW.title), 'A') ||
        setweight(to_tsvector('english', NEW.description), 'B')
      );
    END IF;
  END IF;
  RETURN NEW;
END $$
LANGUAGE plpgsql;

-- Create a trigger before insert or update on game.
CREATE TRIGGER game_search_update
  BEFORE INSERT OR UPDATE ON game
  FOR EACH ROW
  EXECUTE PROCEDURE game_search_update();

-- Finally, create a GIN index for ts_vectors.
CREATE INDEX search_idx ON game USING GIN (tsvectors); 

```

### 3. Triggers
 
> User-defined functions and trigger procedures that add control structures to the SQL language or perform complex computations, are identified and described to be trusted by the database server. Every kind of function (SQL functions, Stored procedures, Trigger procedures) can take base types, composite types, or combinations of these as arguments (parameters). In addition, every kind of function can return a base type or a composite type. Functions can also be defined to return sets of base or composite values.  

| **Trigger**      | TRIGGER01                              |
| ---              | ---                                    |
| **Description**  | Ensures that shared user data (e.g., reviews, likes) is retained but anonymized when a user deletes their account. (ER: BR02 - Delete Account) |
| **Justification** | This trigger ensures user privacy by anonymizing data when an account is deleted, thereby preventing the exposure of personally identifiable information. |
```sql
CREATE FUNCTION anonymize_user_data(user_id INT) RETURNS VOID AS
$BODY$
BEGIN
    -- Anonymize data in Users table
    UPDATE Users
    SET username = 'Anonymous' || user_id,
        name = 'Anonymous',
        email = 'anonymous' || user_id || '@example.com',
        password = 'anonymous'
    WHERE id = user_id;

    -- Anonymize data in Buyer table
    UPDATE Buyer
    SET NIF = NULL,
        birth_date = '1111-11-11', -- Placeholder date
        coins = 0
    WHERE id = user_id;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trg_anonymize_user_data
BEFORE UPDATE ON Users
FOR EACH ROW
WHEN (NEW.is_active IS FALSE)  -- Trigger when is_active is set to FALSE
EXECUTE PROCEDURE anonymize_user_data(OLD.id);
``` 

| **Trigger**      | TRIGGER02                              |
| ---              | ---                                    |
| **Description**  | A Buyer can only leave a review for games it has purchased. (ER: BR11 - Purchase-Based Reviews) |
| **Justification** | Ensures integrity by allowing reviews only from verified purchasers, enhancing trust and quality of feedback. |
```sql
CREATE FUNCTION check_review_eligibility() RETURNS TRIGGER AS
$BODY$
BEGIN
    -- Check if the buyer has purchased the game
    IF NOT EXISTS (
        SELECT 1 
        FROM Purchase p
        JOIN Orders o ON p.order_ = o.id
        WHERE p.game = NEW.game 
        AND o.buyer = NEW.author
    ) THEN
        RAISE EXCEPTION 'A buyer can only review games they have purchased.';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trg_check_review_eligibility
BEFORE INSERT ON Review
FOR EACH ROW
EXECUTE PROCEDURE check_review_eligibility();
```

| **Trigger**      | TRIGGER03                              |
| ---              | ---                                    |
| **Description**  | The ***trg_clear_cart_and_wishlist_after_order*** trigger automatically removes items from a buyer's shopping cart and wishlist once an order is placed. It executes the ***clear_cart_and_wishlist_after_order*** function after a new entry in the Orders table, deleting purchased games for the corresponding buyer. |
| **Justification** | This trigger improves user experience by ensuring that buyers do not see items they have already purchased, reducing interface clutter and encouraging exploration of new products. It aligns with business rules for a streamlined purchasing process, enhancing customer satisfaction. |
```sql
CREATE FUNCTION clear_cart_and_wishlist_after_order() RETURNS TRIGGER AS
$BODY$
DECLARE
    game_id INT;
    buyer_id INT;
BEGIN
    -- Retrieve the buyer ID associated with the order
    SELECT id_buyer INTO buyer_id
    FROM Orders
    WHERE id = NEW.id;

    -- Loop through all purchases associated with this order
    FOR game_id IN
        SELECT id_game FROM Purchase WHERE id_order = NEW.id
    LOOP
        -- Delete each game from ShoppingCart for this buyer
        DELETE FROM ShoppingCart
        WHERE buyer = buyer_id
          AND game = game_id;

        -- Delete each game from Wishlist for this buyer
        DELETE FROM Wishlist
        WHERE buyer = buyer_id
          AND game = game_id;
    END LOOP;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trg_clear_cart_and_wishlist_after_order
AFTER INSERT ON Orders
FOR EACH ROW
EXECUTE PROCEDURE clear_cart_and_wishlist_after_order();
```

| **Trigger**      | TRIGGER04                              |
| ---              | ---                                    |
| **Description**  | Automatically updates a game's overall rating in the game table whenever a review is added or removed. |
| **Justification** | Ensures the game's rating reflects the most current reviews, providing accurate information to users and enhancing the integrity of the review system. |
```sql
CREATE FUNCTION update_game_rating_after_review() RETURNS TRIGGER AS
$BODY$
DECLARE
    total_reviews INT;
    recommended_reviews INT;
    rating_percentage INT;
BEGIN
    -- Calculate the total number of reviews for the game
    SELECT COUNT(*) INTO total_reviews
    FROM Review
    WHERE id_game = NEW.id_game;

    -- Calculate the number of recommended reviews (recommend = TRUE) for the game
    SELECT COUNT(*) INTO recommended_reviews
    FROM Review
    WHERE id_game = NEW.id_game AND recommend = TRUE;

    -- Calculate the recommendation percentage and round to the nearest integer
    IF total_reviews > 0 THEN
        rating_percentage := ROUND((recommended_reviews * 100.0) / total_reviews);
    ELSE
        rating_percentage := 0;  -- If no reviews, set rating to 0
    END IF;

    -- Update the overall_rating in the game table
    UPDATE Game
    SET overall_rating = rating_percentage
    WHERE id = NEW.id_game;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trg_update_game_rating_after_review
AFTER INSERT OR DELETE ON Review
FOR EACH ROW
EXECUTE FUNCTION update_game_rating_after_review();
```

| **Trigger**      | TRIGGER05                              |
| ---              | ---                                    |
| **Description**  | 	Prevents a buyer from liking the same review more than once by checking for existing likes before allowing a new entry. (EBD: BR03) |
| **Justification** | Maintains the integrity of the review system by ensuring that each review can only be liked once per buyer, preventing duplicate interactions and skewed like counts. |
```sql
CREATE FUNCTION check_unique_review_like() RETURNS TRIGGER AS
$BODY$
BEGIN
    -- Check if the buyer has already liked this review
    IF EXISTS (
        SELECT 1
        FROM ReviewLike rl
        WHERE rl.review_id = NEW.review_id 
        AND rl.buyer_id = NEW.buyer_id
    ) THEN
        RAISE EXCEPTION 'A buyer can only like a particular review once.';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trg_check_unique_review_like
BEFORE INSERT ON ReviewLike
FOR EACH ROW
EXECUTE PROCEDURE check_unique_review_like();
```

| **Trigger**      | TRIGGER06                              |
| ---              | ---                                    |
| **Description**  | Validates that a buyer meets the minimum age requirement before allowing a purchase of a game. (ER: C01 - Game Age Restriction) |
| **Justification** | Protects against underage purchases, ensuring compliance with age-related regulations and promoting responsible gaming. |
```sql
CREATE FUNCTION validate_age_for_purchase() RETURNS TRIGGER AS 
$BODY$
DECLARE
    buyer_age INT;
    game_minimum_age INT;
BEGIN
    -- Calculate the buyer's age based on birth_date from the buyer table
    SELECT EXTRACT(YEAR FROM AGE(CURRENT_DATE, birth_date)) INTO buyer_age
    FROM buyer
    WHERE id = NEW.id_buyer;

    -- Get the minimum age required to purchase the game
    SELECT minimum_age INTO game_minimum_age
    FROM game
    WHERE id = NEW.id_game;

    -- Check if the buyer meets the age requirement
    IF buyer_age < game_minimum_age THEN
        RAISE EXCEPTION 'Purchase denied: Buyer does not meet the minimum age requirement of % years for this game.', game_minimum_age;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_validate_age_for_purchase
BEFORE INSERT ON purchase
FOR EACH ROW
EXECUTE FUNCTION validate_age_for_purchase();
```

| **Trigger**      | TRIGGER07                              |
| ---              | ---                                    |
| **Description**  | Automatically converts all pre-purchases into delivered purchases when CDKs for a sold-out or unreleased game become available on the website. This ensures that users who have shown interest in a game receive immediate access to their purchases as soon as the game is available. |
| **Justification** |Enhances user experience by providing instant fulfillment of pre-orders, minimizing wait times, and ensuring that customers can start using their purchased games immediately upon release. It allows the website to respond quickly to inventory changes, aligning with customer expectations for timely access to new content.|
```sql

-- Step 1: Create the Trigger Function
CREATE OR REPLACE FUNCTION process_prepurchase_on_cdk_addition()
RETURNS TRIGGER AS $$
DECLARE
    pre_purchase_record RECORD;
BEGIN
    -- Loop through all pre-purchase records that match the game of the new CDK
    FOR pre_purchase_record IN
        SELECT id, game
        FROM PrePurchase
        WHERE game = NEW.game
    LOOP
        -- Delete the matching pre-purchase record
        DELETE FROM PrePurchase WHERE id = pre_purchase_record.id;

        -- Insert a new record into DeliveredPurchase using the same purchase ID and new CDK ID
        INSERT INTO DeliveredPurchase (id, cdk)
        VALUES (pre_purchase_record.id, NEW.id);
    END LOOP;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Step 2: Create the Trigger to Activate the Function
CREATE TRIGGER trigger_process_prepurchase_on_cdk_addition
AFTER INSERT ON CDK
FOR EACH ROW
EXECUTE FUNCTION process_prepurchase_on_cdk_addition();

```

| **Trigger**      | TRIGGER08                              |
| ---              | ---                                    |
| **Description**  |This trigger automatically increases the number of sCoins for buyers with each purchase, awarding one sCoin for every 10 euros spent.|
| **Justification** |By rewarding buyers with sCoins, the trigger enhances customer engagement and loyalty, fostering a positive shopping experience. It encourages users to make more purchases, as they see a direct benefit from their spending.|
```sql

-- Step 1: Create the Trigger Function
CREATE OR REPLACE FUNCTION add_scoin_on_purchase() 
RETURNS TRIGGER AS $$
DECLARE
    buyer_id INT;
    purchase_value FLOAT;
    scoin_reward INT;
BEGIN
    -- Find the buyer ID and purchase value for the new purchase
    SELECT o.buyer, p.value INTO buyer_id, purchase_value
    FROM Purchase p
    JOIN Orders o ON p.order_ = o.id
    WHERE p.id = NEW.id;

    -- Only proceed if no SCoins were used in the Purchase
    IF NEW.coins = 0 THEN
        -- Calculate SCoins reward: 1 SCoin per $10 spent
        scoin_reward := FLOOR(purchase_value / 10);

        -- Update the buyer's coins with the calculated SCoins
        UPDATE Buyer
        SET coins = coins + scoin_reward
        WHERE id = buyer_id;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Step 2: Create the Trigger
CREATE TRIGGER add_scoin_on_purchase
AFTER INSERT ON Purchase
FOR EACH ROW
EXECUTE FUNCTION add_scoin_on_purchase();

```
| **Trigger**      | TRIGGER09                             |
| ---              | ---                                    |
| **Description**  |This trigger is activated after a new row is inserted in the Purchase table. When a purchase is completed, it decreases the coins balance of the associated Buyer by the number of SCoins used in that purchase.|
| **Justification** |This trigger ensures that the Buyer's coins balance accurately reflects the SCoins spent on purchases, automating this process and maintaining data consistency without requiring manual updates.|

```sql

-- Create the function to decrease Scoins
CREATE OR REPLACE FUNCTION decrease_scoins()
RETURNS TRIGGER AS $$
BEGIN
    -- Decrease the buyer's Scoins by the sCoins amount specified in the Purchase
    UPDATE Buyer
    SET coins = coins - NEW.coins
    WHERE id = (SELECT buyer FROM Orders WHERE id = NEW.order_);
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create the trigger to fire after an insert on the Purchase table
CREATE TRIGGER decrease_scoins_on_purchase
AFTER INSERT ON Purchase
FOR EACH ROW
EXECUTE FUNCTION decrease_scoins();

```

| **Trigger**      | TRIGGER10                             |
| ---              | ---                                    |
| **Description**  |This trigger executes after a new row is inserted into the DeliveredPurchase table, indicating that a game has been delivered to a buyer. The trigger function, decrement_game_stock, checks the current stock for the purchased game and decrements the stock quantity by 1 only if the stock is greater than 0.|
| **Justification** |This trigger maintains inventory accuracy by ensuring the game stock reflects actual sales. This automated stock management helps to reduce manual oversight and ensures real-time updates, leading to more reliable inventory data and an improved customer experience by preventing sales of out-of-stock items.|

```sql

-- Create the function to decrement game stock with a stock check
CREATE OR REPLACE FUNCTION decrement_game_stock()
RETURNS TRIGGER AS $$
BEGIN
    -- Decrement the quantity only if it is greater than 0
    UPDATE GameStock
    SET quantity = quantity - 1
    WHERE game = (SELECT game FROM CDK WHERE id = NEW.cdk) AND quantity > 0;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create the trigger to fire after an insert on the DeliveredPurchase table
CREATE TRIGGER decrement_game_stock
AFTER INSERT ON DeliveredPurchase
FOR EACH ROW
EXECUTE FUNCTION decrement_game_stock();

```

| **Trigger**      | TRIGGER11                             |
| ---              | ---                                    |
| **Description**  |This trigger fires when a row is inserted into the CanceledPurchase table, indicating that a game purchase has been canceled. The trigger function, increment_game_stock, locates the specific game associated with the canceled purchase and increments the stock quantity by 1.|
| **Justification** |This trigger ensures that game stock remains accurate by automatically updating inventory levels when purchases are canceled. By incrementing the stock upon cancellation, the system can accurately reflect the availability of the game, helping to prevent lost sales opportunities and providing customers with a reliable view of product availability. This reduces the need for manual stock adjustments and supports consistent, real-time inventory management.|

```sql

-- Create the function to increment game stock
CREATE OR REPLACE FUNCTION increment_game_stock()
RETURNS TRIGGER AS $$
BEGIN
    -- Increment the quantity of the respective game in the GameStock table
    UPDATE GameStock
    SET quantity = quantity + 1
    WHERE game = NEW.game;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create the trigger to fire after an insert on the CDK table
CREATE TRIGGER update_game_stock
AFTER INSERT ON CDK
FOR EACH ROW
EXECUTE FUNCTION increment_game_stock();

```

### 4. Transactions
 
> Transactions needed to assure the integrity of the data.  

| SQL Reference   | Transaction Name                    |
| --------------- | ----------------------------------- |
| Justification   | Justification for the transaction.  |
| Isolation level | Isolation level of the transaction. |
| `Complete SQL Code`                                   ||


## Annex A. SQL Code

> The database scripts are included in this annex to the EBD component.
> 
> The database creation script and the population script should be presented as separate elements.
> The creation script includes the code necessary to build (and rebuild) the database.
> The population script includes an amount of tuples suitable for testing and with plausible values for the fields of the database.
>
> The complete code of each script must be included in the group's git repository and links added here.

### A.1. Database schema

> The complete database creation must be included here and also as a script in the repository.

### A.2. Database population

> Only a sample of the database population script may be included here, e.g. the first 10 lines. The full script must be available in the repository.

---


## Revision history

Changes made to the first submission:
1. Item 1
1. ..

***
GROUPYYgg, DD/MM/20YY
 
* Group member 1 name, email (Editor)
* Group member 2 name, email
* ...