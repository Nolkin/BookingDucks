 CREATE  TABLE  `api_db`.`ducks` (  `id` int( 11  )  NOT  NULL  AUTO_INCREMENT ,
 `price` int( 11  )  NOT  NULL ,
 `color` varchar( 256  )  NOT  NULL ,
 `owner` varchar( 256  )  NOT  NULL ,
 `created` datetime NOT  NULL ,
  PRIMARY  KEY (  `id`  )  ) ENGINE  = InnoDB  DEFAULT CHARSET  = utf8