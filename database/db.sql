CREATE TABLE tbl_user (
    id int primary key auto_increment,
    name varchar(128) not null,
    email varchar(50) unique,
    password varchar(100) not null,
    contact char(10) not null,
    user_type char(1) default 'C', -- C : Customer, V : vendor, A : Admin
    status int default 1
);

insert into tbl_user values 
(default,'Natsha','natasha@gmail.com','$2y$10$llVLT8xXfcSXdlILN8JZpegR5V1kR8W58sVLhKjQk5REG/3R8vEXW','8956235689','V',default),
(default,'Steve','steve@gmail.com','$2y$10$llVLT8xXfcSXdlILN8JZpegR5V1kR8W58sVLhKjQk5REG/3R8vEXW','8956235681','V',default),
(default,'Bruce','bruce@gmail.com','$2y$10$llVLT8xXfcSXdlILN8JZpegR5V1kR8W58sVLhKjQk5REG/3R8vEXW','8956235680','C',default),
(default,'Scott','scott@gmail.com','$2y$10$llVLT8xXfcSXdlILN8JZpegR5V1kR8W58sVLhKjQk5REG/3R8vEXW','8956235640','C',default),
(default,'Stark','stark@gmail.com','$2y$10$llVLT8xXfcSXdlILN8JZpegR5V1kR8W58sVLhKjQk5REG/3R8vEXW','8956235683','A',default);

CREATE TABLE tbl_venue (
    id int primary key auto_increment,
    name varchar(50) not null,
    address varchar(50) not null,
    price float not null,
    capacity int not null,
    image_path varchar(250) not null,
    status int default 1
);

insert into tbl_venue values 
(default,'Sai Lila','Eru Navsari',5000,100,'../assets/venue_img/default.png',1),
(default,'Horizon','Italva Navsari',10000,100,'../assets/venue_img/default.png',1);
 
CREATE TABLE tbl_vendor_data (
    id int primary key auto_increment,
    name varchar(20), 
    category varchar(20), ---- dj,catering
    price float,
    details varchar(250), -- dj set daaetails, catering details
    vid int, -- vendor id
    status int default 1
);

insert into tbl_vendor_data values 
(default,'Sai DJ','dj',15000,'4 Top, 4 Bass',1,1),
(default,'STC Catering','catering',25000,'Gujarati, Panjabi',2,1);

CREATE TABLE tbl_booking (
    id int primary key auto_increment,
    cid int, -- customer id
    vdid int, -- vendor data id
    vnid int, -- venue id
    booked_date date,
    time char(18) not null, -- format 00:00am to 00:00pm
    order_date timestamp default current_timestamp,
    order_status int default 0, -- 0 pending
    total_amount float,
    foreign key (cid) references tbl_user(id),
    foreign key (vdid) references tbl_vendor_data(id),
    foreign key (vnid) references tbl_venue(id)
);

insert into tbl_booking values 
(default,3,1,1,'2023-04-15','09:00am to 05:30pm',default,0,20000),
(default,4,2,4,'2023-04-17','09:00am to 05:30pm',default,1,30000),
(default,4,2,1,'2023-04-14','09:00am to 05:30pm',default,0,15000),
(default,3,1,5,'2023-04-19','10:00am to 05:30pm',default,1,20000);

SELECT tvd.name name,tv.name venue,tv.address,booked_date,order_date,
    CASE WHEN order_status=1 THEN 'Completed' ELSE 'Pending' END status,total_amount  
FROM tbl_booking tb,tbl_vendor_data tvd, tbl_venue tv, tbl_user tu
WHERE 
    tb.cid=tu.id AND
    tb.vdid=tvd.id AND
    tb.vnid=tv.id AND tb.cid=4 AND tb.order_status=1;
    

SELECT tb.id oid,tu.name,tu.email,tu.contact,tv.name title,tv.address,booked_date,order_date,vid,order_status  FROM tbl_booking tb,tbl_vendor_data tvd, tbl_venue tv, tbl_user tu
WHERE 
    tb.cid=tu.id AND
    tb.vdid=tvd.id AND
    tb.vnid=tv.id AND vid=1
    ;

CREATE TABLE tbl_cart (
    id int,
    cid int,
    primary key(id,cid)
);

SELECT Concat(name,' ',category) name,price,tc.id FROM tbl_cart tc, tbl_vendor_data tvd
WHERE tc.id=tvd.id AND tc.cid=;

CREATE TABLE tbl_cart_venue (
    id int,
    cid int,
    primary key(id,cid)
);

SELECT Concat(name,' ',address,' Capacity:',capacity),price FROM tbl_cart_venue tc, tbl_venue tvd
WHERE tc.id=tvd.id AND tc.cid=;


-- Total amount
SELECT sum(total_amount)  FROM tbl_booking tb,tbl_vendor_data tvd, tbl_venue tv, tbl_user tu
WHERE 
    tb.cid=tu.id AND
    tb.vdid=tvd.id AND
    tb.vnid=tv.id AND tvd.vid=7
    ;

-- vendor price
select sum(tvd.price/100*80) earn from tbl_vendor_data tvd,tbl_booking tb WHERE tvd.id=tb.vdid AND tvd.vid=7;

-- total customer
select count(cid) from tbl_vendor_data tvd,tbl_booking tb WHERE tvd.id=tb.vdid AND tvd.vid=7;

-- total completed
select count(1) completed from tbl_vendor_data tvd,tbl_booking tb WHERE tvd.id=tb.vdid AND tvd.vid=7 AND tb.order_status=1;
-- total pending
select count(1) completed from tbl_vendor_data tvd,tbl_booking tb WHERE tvd.id=tb.vdid AND tvd.vid=7 AND tb.order_status=0;

-- date by
select order_date x, sum(tvd.price/100*80) y from tbl_vendor_data tvd,tbl_booking tb WHERE tvd.id=tb.vdid AND tvd.vid=7 GROUP BY order_date;

select monthname(order_date) x, sum(tvd.price/100*80) y from tbl_vendor_data tvd,tbl_booking tb WHERE tvd.id=tb.vdid AND tvd.vid=7 GROUP BY monthname(order_date)


-- admin revenue from vendor
select sum(tvd.price/100*20) earn from tbl_vendor_data tvd,tbl_booking tb WHERE tvd.id=tb.vdid;

select 
    tu.name,
    tvd.category,
    sum(tvd.price/100*80) earn
from
    tbl_booking tb, tbl_vendor_data tvd, tbl_user tu
where tu.id=tvd.vid AND tb.vdid=tvd.id
GROUP BY tu.name,tvd.category order by earn desc;

SELECT   FROM tbl_booking tb,tbl_vendor_data tvd, tbl_venue tv, tbl_user tu
WHERE 
    tb.cid=tu.id AND
    tb.vdid=tvd.id AND
    tb.vnid=tv.id AND tvd.vid=7
    ;

CREATE TABLE tbl_payment_log (
    id int primary key auto_increment,
    payment_id varchar(512),
    bid int, -- booking id
    cid int, -- customer id
    datetime datetime default current_timestamp,
    status int -- 0 fail, 1 done
);

