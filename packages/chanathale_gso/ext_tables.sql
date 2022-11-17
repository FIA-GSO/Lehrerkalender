#
# Table structure for table 'tx_chanathalegso_domain_model_pupil'
#
CREATE TABLE tx_chanathalegso_domain_model_pupil
(
    firstname    varchar(255) DEFAULT '' NOT NULL,
    lastname     varchar(255) DEFAULT '' NOT NULL,
    pupil_number varchar(255) DEFAULT '' NOT NULL,
    email        TEXT         DEFAULT '' NOT NULL,
    classroom    int(11) DEFAULT '0' NOT NULL,
    users        TEXT         DEFAULT '' NOT NULL,
    grades       TEXT         DEFAULT '' NOT NULL,
    url_slug     TEXT         DEFAULT '' NOT NULL
);

#
# Table structure for table 'tx_chanathalegso_domain_model_classroom'
#
CREATE TABLE tx_chanathalegso_domain_model_classroom
(
    title    varchar(255) DEFAULT '' NOT NULL,
    pupils   TEXT         DEFAULT '' NOT NULL,
    users    TEXT         DEFAULT '' NOT NULL,
    url_slug TEXT         DEFAULT '' NOT NULL
);

#
# Table structure for table 'tx_chanathalegso_domain_model_gradetype'
#
CREATE TABLE tx_chanathalegso_domain_model_gradetype
(
    title varchar(255) DEFAULT '' NOT NULL
);

#
# Table structure for table 'tx_chanathalegso_domain_model_subject'
#
CREATE TABLE tx_chanathalegso_domain_model_subject
(
    title      varchar(255) DEFAULT '' NOT NULL,
    users      TEXT         DEFAULT '' NOT NULL,
    classrooms TEXT         DEFAULT '' NOT NULL,
    color      TEXT         DEFAULT '' NOT NULL,
    url_slug   TEXT         DEFAULT '' NOT NULL
);

#
# Table structure for table 'tx_chanathalegso_domain_model_performance'
#
CREATE TABLE tx_chanathalegso_domain_model_performance
(
    title       varchar(255) DEFAULT '' NOT NULL,
    comment     TEXT         DEFAULT '' NOT NULL,
    author      int(11) DEFAULT 0 NOT NULL,
    pupil       int(11) DEFAULT 0 NOT NULL,
    subject     int(11) DEFAULT 0 NOT NULL,
    grade_type  int(11) DEFAULT 0 NOT NULL,
    grade       varchar(255) DEFAULT '' NOT NULL,
    create_date int(11) DEFAULT 0 NOT NULL
);

#
# Table structure for table 'tx_chanathalegso_domain_model_room'
#
CREATE TABLE tx_chanathalegso_domain_model_room
(
    title varchar(255) DEFAULT '' NOT NULL
);

#
# Table structure for table 'tx_chanathalegso_domain_model_meeting'
#
CREATE TABLE tx_chanathalegso_domain_model_meeting
(
    topic               varchar(255) DEFAULT '' NOT NULL,
    room                int(11) DEFAULT '0' NOT NULL,
    create_date         int(11) DEFAULT '0' NOT NULL,
    start_time          varchar(255) DEFAULT '' NOT NULL,
    duration            varchar(255) DEFAULT '' NOT NULL,
    author              int(11) DEFAULT 0 NOT NULL,
    full_calendar_start varchar(255) DEFAULT '' NOT NULL,
    full_calendar_end   varchar(255) DEFAULT '' NOT NULL
);