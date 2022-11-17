#
# Table structure for table 'tx_chanathalecustomer_domain_model_social'
#
CREATE TABLE tx_chanathalecustomer_domain_model_social
(
    css varchar(255) DEFAULT '' NOT NULL,
    url TEXT         DEFAULT '' NOT NULL
);

#
# Table structure for table 'pages'
#
CREATE TABLE pages
(
    fe_user_protected int(11) unsigned NULL DEFAULT '0'
);