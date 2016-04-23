-- 1. a. Ability to view, add, remove and modify customer information.
-- ---------------------------------------------------------------------------------
-- EXAMPLES
SELECT * FROM customer;  -- Add restrictions to get single rows as well

INSERT INTO customer(customerID, Fname, Lname, address, city, state, zip)
VALUES ('','','','','','','');

DELETE FROM customer WHERE customerID = '';

UPDATE customer
SET Fname = ''
WHERE customerID = '';

-- 1. b. Ability to view, add, remove and modify supplier information.
-- ---------------------------------------------------------------------------------
-- EXAMPLES
SELECT * FROM supplier;  -- Add restrictions to get single rowsas well

INSERT INTO supplier(Sname, city, zip)
VALUES ('','','');

DELETE FROM supplier WHERE Sname = '';

UPDATE supplier
SET city = ''
WHERE Sname = '';

-- 1. c. Ability to view, add, remove and modify product information.
-- ---------------------------------------------------------------------------------
-- EXAMPLES
SELECT * FROM product; -- Add restrictions to get single rowsas well

INSERT INTO product(UPC, Pname, price, Sname, ammount, reorderlevel)
VALUES ('','','','','','');

DELETE FROM product WHERE UPC = '';

UPDATE product
SET Pname = ''
WHERE UPC = '';

-- 1. d. Ability to view inventory.
-- ---------------------------------------------------------------------------------
SELECT * FROM product;

-- 1. e. Ability to modify inventory (as a result of re-ordering).
-- ---------------------------------------------------------------------------------
DELIMITER //
CREATE PROCEDURE reorder (IN UPC_IN VARCHAR(5), IN ammount_IN INT)
BEGIN
  UPDATE product
	SET ammount = ammount + ammount_IN
	WHERE UPC = UPC_IN;
END //
DELIMITER ;

CALL reorder('00001', -5); -- Adds 5 to UPC 00001 in stock (product)

-- 1. f. Ability to load information from flat files.
-- ---------------------------------------------------------------------------------
LOAD DATA LOCAL INFILE '' INTO TABLE customer
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\r\n';

LOAD DATA LOCAL INFILE '' INTO TABLE supplier
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\r\n';

LOAD DATA LOCAL INFILE '' INTO TABLE product
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\r\n';

-- 1. g.        Ability to place an order.
-- ---------------------------------------------------------------------------------
-- EXAMPLE -- ID for orders Auto Increments
INSERT INTO orders(orderdate,shipdate,payment_type,CCN, customerID)
VALUES (CURDATE(), NULL, 'DISCOVER', '3245369873455558', 4);

-- 1. h. Ability to track an order.
-- ---------------------------------------------------------------------------------
DELIMITER //
CREATE PROCEDURE trackOrder (IN order_id INT)
BEGIN
  SELECT *
  from orders
  where orderID = order_id;
END //
DELIMITER ;

-- 1. i. Ability to view, add and remove products on the wish list.
-- ---------------------------------------------------------------------------------
DELIMITER //
CREATE PROCEDURE trackOrder (IN order_id INT)
BEGIN
  SELECT *
  from orders
  where orderID = order_id;
END //
DELIMITER ;
-- 1. j. Ability to rate products.
-- ---------------------------------------------------------------------------------
-- NOTE: use this to check if customer has rated that product yet...
CREATE PROCEDURE getRating (IN customer_id INT, IN UPC_IN varchar(5))
BEGIN
  SELECT *
	from rated
	WHERE UPC = UPC_IN and customerID = customer_id;
END //
DELIMITER ;
-- ...Then use this if they have already rated, and wish to update
DELIMITER //
CREATE PROCEDURE upadateRateProduct (IN customer_id INT, IN UPC_IN varchar(5), IN new_rating INT)
BEGIN
  UPDATE rated
	SET rating = new_rating
	WHERE UPC = UPC_IN and customerID = customer_id;
END //
DELIMITER ;
-- Else, use this and add a new rating to the table
DELIMITER //
CREATE PROCEDURE insertRateProduct (IN customer_id INT, IN UPC_IN varchar(5), IN new_rating INT)
BEGIN
	INSERT INTO rated(csutomerID, UPC, rating, ratingdate)
	VALUES (customer_id, UPC_IN, new_rating, NOW());
END //
DELIMITER ;

-- 2. k. List of products whose inventory is at reorder level.
-- ---------------------------------------------------------------------------------
SELECT * FROM product
WHERE ammount <= reorderlevel;

-- 2. l. Gets the customer who haven't ordered anyting in the past n days
-- ---------------------------------------------------------------------------------
DELIMITER //
CREATE PROCEDURE getInactiveCustomers (IN days_IN INT)
BEGIN
  SELECT customerID, MAX(orderdate) as date_of_last_order FROM customer LEFT OUTER JOIN orders using (customerID)
	WHERE customerID NOT IN (
	SELECT customerID FROM orders
    WHERE orderdate BETWEEN DATE_SUB(CURDATE(), INTERVAL days_IN day) AND CURDATE())
GROUP BY customerID;
END //
DELIMITER ;


-- 2. m. List of products that are not selling “too well”(*), which might 
-- 2. m. be offered as specials.
-- ---------------------------------------------------------------------------------
DELIMITER //
Create Procedure getSellingPoorly (in selling_poorly_limit int) 
begin
	select *
    from product
    where UPC not in (select UPC
						from contains
						having count(UPC) > selling_poorly_limit);
END //
DELIMITER ; 

-- 2. o. List of highly rated products.
-- ---------------------------------------------------------------------------------
DELIMITER //
Create Procedure getHighestRated () 
begin
	select rating, UPC, rateingdate
    from rated
    where rating = 4 or rating = 5
    group by UPC ;
END //
DELIMITER ; 

CALL getInactiveCustomers(5);


-- 2. p. List of highly wished products.
-- wished limit is the minimum count of an individual item that must b eusrpased in order for it to be cosidered highly wished
-- ---------------------------------------------------------------------------------
DELIMITER //
Create Procedure getHighestWished (IN wished_limit INT) 
begin
	select UPC, count(*)
    from wishes
    group by UPC
    having count(*) > wished_limit;
END //
DELIMITER ;

call getHighestWished(10);

-- 2. q. List of wished products that have never been bought by the customers who 
-- 2. q. wish them.
-- ---------------------------------------------------------------------------------
DELIMITER //
Create Procedure getWishedButNotBought () 
begin
	select UPC, customerID
    from wishes AS S
    where UPC not in (Select UPC
						from orders
                        where S.customerID = orders.customerID)
	order by customerID asc;
END //
DELIMITER ;
-- for use of finding a given customer's wished but not bought list
DELIMITER //
Create Procedure getWishedButNotBoughtByID (IN customer_id int) 
begin
	select UPC, customerID
    from wishes AS S
    where customerID = 7 and UPC not in (Select UPC
													from orders
													where S.customerID = orders.customerID);
END //
DELIMITER ; 

-- 2. r. List of customers who rated products they did not buy.
-- ---------------------------------------------------------------------------------
DELIMITER //
Create Procedure getRatedButNotBought () 
begin
	select UPC, rateingdate, customerID
    from rated AS S
    where UPC not in (Select UPC
						from orders
                        where S.customerID = orders.customerID)
	order by customerID asc;
END //
DELIMITER ; 

-- 2. s. List of customers who did not rate any products they bought. Bob
-- ---------------------------------------------------------------------------------
DELIMITER //
Create Procedure getBoughtButNotRated () 
begin
	select UPC, customerID
    from rated
    where UPC not in (select UPC
						from contains join orders using (orderID)
                        where rated.customerID = customerID)
	order by customerID asc;
END //
DELIMITER ; 

-- Through the Customer component:
-- 2. t. List of bestselling products. Bob
-- ---------------------------------------------------------------------------------
DELIMITER //
Create Procedure getBestSelling (in sell_limit int) 
begin
	select UPC
    from contains
    group by UPC
    having count(UPC) > sell_limit;
END //
DELIMITER ; 

-- 2. u. A suggested list of products for each customer.
-- Given a customer ID, find products not bought by them
-- ---------------------------------------------------------------------------------
DELIMITER //
Create Procedure getSuggestedForCustomer (in customer_id int(11)) 
begin
	select UPC, category
    from prod_category
    where UPC not in (select UPC
					from contains
					where orderID in (select orderID
										from orders
										where customerID = customer_id))
	and category in (select category
						from prod_category
						where UPC in (select UPC
										from contains
										where orderID in (select orderID
															from orders
															where customerID = customer_id))
						group by category
						having count(category) > 5)
	order by category;
END //
DELIMITER ; 
