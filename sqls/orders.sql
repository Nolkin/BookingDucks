 CREATE  TABLE  `api_db`.`orders` (  `id` int( 11  )  NOT  NULL  AUTO_INCREMENT ,
 `owner` varchar( 255  )  NOT  NULL ,
 `created` timestamp NOT  NULL DEFAULT current_timestamp(  ) ,
 PRIMARY  KEY (  `id`  )  ) ENGINE  = InnoDB  DEFAULT CHARSET  = utf8