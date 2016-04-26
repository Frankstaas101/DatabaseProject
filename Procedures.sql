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

call trackOrder(1);

-- 1. i. Ability to view, add and remove products on the wish list.
-- ---------------------------------------------------------------------------------
DELIMITER //
CREATE PROCEDURE getWishesByUPC (IN UPC_IN varchar(5))
BEGIN
  SELECT *
  from wishes
  where UPC = UPC_IN;
END //
DELIMITER ;

call getWishesByUPC('00001');

DELIMITER //
CREATE PROCEDURE addWishesByUPC (IN customer_ID int, IN UPC_IN varchar(5))
BEGIN
  INSERT INTO wishes(customerID, UPC)
	VALUES (customer_ID, UPC_IN);
END //
DELIMITER ;

call addWishesByUPC(1, '00001');

-- 1. j. Ability to rate products
-- ---------------------------------------------------------------------------------
-- NOTE: use this to check if customer has rated that product yet...
DELIMITER //
CREATE PROCEDURE getRating (IN customer_id INT, IN UPC_IN varchar(5))
BEGIN
  SELECT *
	from rated
	WHERE UPC = customer_ID and customerID = UPC_IN;
END //
DELIMITER ;

call getRating(1, '00001');

-- ...Then use this if they have already rated, and wish to update
DELIMITER //
CREATE PROCEDURE upadateRateProduct (IN customer_id INT, IN UPC_IN varchar(5), IN new_rating INT)
BEGIN
  UPDATE rated
	SET rating = new_rating
	WHERE UPC = UPC_IN and customerID = customer_id;
END //
DELIMITER ;

call upadateRateProduct(1, '00001', 1);

-- Else, use this and add a new rating to the table
DELIMITER //
CREATE PROCEDURE insertRateProduct (IN customer_id INT, IN UPC_IN varchar(5), IN new_rating INT)
BEGIN
	INSERT INTO rated(csutomerID, UPC, rating, ratingdate)
	VALUES (customer_id, UPC_IN, new_rating, NOW());
END //
DELIMITER ;

call insertRateProduct(1, '00001', 5);

-- 2. k. List of products whose inventory is at reorder level. EMPTY SET
-- ---------------------------------------------------------------------------------
DELIMITER //
CREATE PROCEDURE getBelowReorderLevel ()
BEGIN
	SELECT * FROM product
	WHERE ammount <= reorderlevel;
END //
DELIMITER ;

call getBelowReorderLevel();

-- 2. l. Gets the customer who haven't ordered anyting in the past n days
-- ---------------------------------------------------------------------------------
DELIMITER //
CREATE PROCEDURE getInactiveCustomers (IN days_IN INT)
BEGIN
  SELECT customerID, MAX(orderdate) as date_of_last_order FROM customer LEFT OUTER JOIN orders using (customerID)
	WHERE customerID NOT IN (
	SELECT customerID FROM orders
    WHERE orderdate BETWEEN DATE_SUB(CURDATE(), INTERVAL 5 day) AND CURDATE())
GROUP BY customerID;
END //
DELIMITER ;

call getInactiveCustomers(100);

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
						group by UPC
						having count(UPC) > 3);
END //
DELIMITER ;

call getSellingPoorly(9);

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

CALL getHighestRated();


-- 2. p. List of highly wished products.
-- wished limit is the minimum count of an individual item that must b eusrpased in order for it to be cosidered highly wished
-- ---------------------------------------------------------------------------------
DELIMITER //
Create Procedure getHighestWished (IN wished_limit INT) 
begin
	select UPC, count(*)
    from wishes
    group by UPC
    having count(*) > 9;
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

call getWishedButNotBought();

-- for use of finding a given customer's wished but not bought list
DELIMITER //
Create Procedure getWishedButNotBoughtByID (IN customer_id int) 
begin
	select UPC, customerID
    from wishes AS S
    where customerID = customer_id and UPC not in (Select UPC
													from orders
													where S.customerID = orders.customerID);
END //
DELIMITER ;

call getWishedButNotBoughtByID(7);

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

call getRatedButNotBought();

-- 2. s. List of customers who did not rate any products they bought.
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

call getBoughtButNotRated();

-- Through the Customer component:
-- 2. t. List of bestselling products. Bob
-- ---------------------------------------------------------------------------------
-- sell limit is the minimum count which a product must have within all of the contains table in order to be considered 'best selling'
DELIMITER //
Create Procedure getBestSellingByLimit (in sell_limit int) 
begin
	select UPC
    from contains
    group by UPC
    having count(UPC) > sell_limit;
END //
DELIMITER ;

call getBestSellingByLimit(9);

-- num_limit is the top x ammount of best sellers you want to retain, ordered, desc, by the their UPC's respective count in the conatains tabale
DELIMITER //
Create Procedure getBestSellingTopNum (in num_limit int) 
begin
	select UPC
    from contains
    group by UPC
    order by count(*) desc
    limit num_limit;
END //
DELIMITER ; 

call getBestSellingTopNum(5);

-- 2. u. A suggested list of products for each customer.
-- Given a customer ID, find products not bought by them, t5hen juxtaposed with the commonly bough tby catgeory return the result
-- ---------------------------------------------------------------------------------
DELIMITER //
Create Procedure getSuggestedForCustomer (in customer_id int)
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

call getSuggestedForCustomer(13);
