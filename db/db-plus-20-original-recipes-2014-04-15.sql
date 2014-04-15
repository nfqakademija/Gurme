CREATE DATABASE  IF NOT EXISTS `symfony` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `symfony`;
-- MySQL dump 10.13  Distrib 5.5.35, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: symfony
-- ------------------------------------------------------
-- Server version	5.5.35-0+wheezy1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Appetizers'),(2,'BBQ and grilling'),(3,'Beef'),(4,'Beverages'),(5,'Bread'),(6,'Breakfast/Brunch'),(7,'Cakes'),(8,'Chicken'),(9,'Cookies'),(10,'Desserts'),(11,'Family'),(12,'Healthy Cooking'),(13,'Holidays'),(14,'Italian'),(15,'Kid-friendly'),(16,'Lifestyle'),(17,'Main Dish'),(18,'Meal ideas'),(19,'Mexican'),(20,'Other ethnic food'),(21,'Pasta'),(22,'Pork'),(23,'Quick and Easy'),(24,'Restaurants'),(25,'Salad'),(26,'Seafood'),(27,'Seasonal cooking'),(28,'Side-dish'),(29,'Slow Cooker'),(30,'Soups and stews'),(31,'Vegetarian');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingredients`
--

DROP TABLE IF EXISTS `ingredients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingredients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `alias` longtext COMMENT '(DC2Type:simple_array)',
  `popularity` int(11) DEFAULT NULL,
  `icon_url` varchar(2083) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredients`
--

LOCK TABLES `ingredients` WRITE;
/*!40000 ALTER TABLE `ingredients` DISABLE KEYS */;
INSERT INTO `ingredients` VALUES (1,'canned garbanzo beans',NULL,NULL,NULL),(2,'tahini\r',NULL,NULL,NULL),(3,'lemon juice\r',NULL,NULL,NULL),(4,'salt\r',NULL,NULL,NULL),(5,'cloves garlic',NULL,NULL,NULL),(6,'olive oil\r',NULL,NULL,NULL),(7,'pinch paprika\r',NULL,NULL,NULL),(8,'minced fresh parsley',NULL,NULL,NULL),(9,'large potatoes',NULL,NULL,NULL),(10,'ghee \r',NULL,NULL,NULL),(11,'cumin seeds\r',NULL,NULL,NULL),(12,'green chile peppers',NULL,NULL,NULL),(13,'piece fresh ginger root',NULL,NULL,NULL),(14,'chili powder\r',NULL,NULL,NULL),(15,'coriander',NULL,NULL,NULL),(16,'amchoor \r',NULL,NULL,NULL),(17,'bunch fresh cilantro',NULL,NULL,NULL),(18,'angel hair pasta\r',NULL,NULL,NULL),(19,'tomatoes\r',NULL,NULL,NULL),(20,'cloves crushed garlic\r',NULL,NULL,NULL),(21,'chopped fresh basil\r',NULL,NULL,NULL),(22,'tomato paste\r',NULL,NULL,NULL),(23,'salt to taste\r',NULL,NULL,NULL),(24,'ground black pepper to taste\r',NULL,NULL,NULL),(25,'grated Parmesan cheese',NULL,NULL,NULL),(26,'vegetable oil\r',NULL,NULL,NULL),(27,'onions',NULL,NULL,NULL),(28,'fresh ginger root',NULL,NULL,NULL),(29,'whole cloves\r',NULL,NULL,NULL),(30,'sticks cinnamon',NULL,NULL,NULL),(31,'ground cumin\r',NULL,NULL,NULL),(32,'ground coriander\r',NULL,NULL,NULL),(33,'cayenne pepper\r',NULL,NULL,NULL),(34,'ground turmeric\r',NULL,NULL,NULL),(35,'garbanzo beans\r',NULL,NULL,NULL),(36,'chopped fresh cilantro',NULL,NULL,NULL),(37,'balsamic vinaigrette salad dressing\r',NULL,NULL,NULL),(38,'seasoned pepper\r',NULL,NULL,NULL),(39,'dried cilantro\r',NULL,NULL,NULL),(40,'ground cayenne pepper\r',NULL,NULL,NULL),(41,'black beans',NULL,NULL,NULL),(42,'whole kernel corn',NULL,NULL,NULL),(43,'chopped onion\r',NULL,NULL,NULL),(44,'chopped green onions\r',NULL,NULL,NULL),(45,'red bell pepper',NULL,NULL,NULL),(46,'ground turkey\r',NULL,NULL,NULL),(47,'grated carrots\r',NULL,NULL,NULL),(48,'dried basil\r',NULL,NULL,NULL),(49,'minced jalapeno peppers\r',NULL,NULL,NULL),(50,'milk\r',NULL,NULL,NULL),(51,'white wine\r',NULL,NULL,NULL),(52,'whole peeled tomatoes\r',NULL,NULL,NULL),(53,'spaghetti\r',NULL,NULL,NULL),(54,'lean ground beef\r',NULL,NULL,NULL),(55,'salt and pepper to taste\r',NULL,NULL,NULL),(56,'dark red kidney beans\r',NULL,NULL,NULL),(57,'Mexican-style stewed tomatoes\r',NULL,NULL,NULL),(58,'stalks celery',NULL,NULL,NULL),(59,'red wine vinegar\r',NULL,NULL,NULL),(60,'dried parsley\r',NULL,NULL,NULL),(61,'dash Worcestershire sauce\r',NULL,NULL,NULL),(62,'red wine',NULL,NULL,NULL),(63,'oil\r',NULL,NULL,NULL),(64,'uncooked white rice\r',NULL,NULL,NULL),(65,'chicken broth\r',NULL,NULL,NULL),(66,'chunky salsa',NULL,NULL,NULL),(67,'boneless chicken breast halves\r',NULL,NULL,NULL),(68,'honey\r',NULL,NULL,NULL),(69,'prepared mustard\r',NULL,NULL,NULL),(70,'paprika\r',NULL,NULL,NULL),(71,'dried parsley',NULL,NULL,NULL),(72,'active dry yeast\r',NULL,NULL,NULL),(73,'brown sugar\r',NULL,NULL,NULL),(74,'warm water \r',NULL,NULL,NULL),(75,'all-purpose flour',NULL,NULL,NULL),(76,'dried split peas\r',NULL,NULL,NULL),(77,'cold water\r',NULL,NULL,NULL),(78,'ham bone\r',NULL,NULL,NULL),(79,'ground black pepper\r',NULL,NULL,NULL),(80,'pinch dried marjoram\r',NULL,NULL,NULL),(81,'carrots',NULL,NULL,NULL),(82,'potato',NULL,NULL,NULL),(83,'minced onion\r',NULL,NULL,NULL),(84,'crushed garlic\r',NULL,NULL,NULL),(85,'poultry seasoning\r',NULL,NULL,NULL),(86,'soy sauce\r',NULL,NULL,NULL),(87,'artificial sweetener',NULL,NULL,NULL),(88,'butter\r',NULL,NULL,NULL),(89,'leeks',NULL,NULL,NULL),(90,'large head cauliflower',NULL,NULL,NULL),(91,'vegetable broth\r',NULL,NULL,NULL),(92,'salt and freshly ground black pepper to taste\r',NULL,NULL,NULL),(93,'heavy cream ',NULL,NULL,NULL),(94,'sugar-free strawberry Jell-O® mix\r',NULL,NULL,NULL),(95,'granulated no-calorie sugar substitute \r',NULL,NULL,NULL),(96,'egg whites at room temperature\r',NULL,NULL,NULL),(97,'cream of tartar\r',NULL,NULL,NULL),(98,'salt',NULL,NULL,NULL),(99,'unsalted butter\r',NULL,NULL,NULL),(100,'chopped onions\r',NULL,NULL,NULL),(101,'fresh mushrooms',NULL,NULL,NULL),(102,'dried dill weed\r',NULL,NULL,NULL),(103,'all-purpose flour\r',NULL,NULL,NULL),(104,'chopped fresh parsley\r',NULL,NULL,NULL),(105,'sour cream',NULL,NULL,NULL),(106,'habanero peppers',NULL,NULL,NULL),(107,'small onion',NULL,NULL,NULL),(108,'bunches green onions',NULL,NULL,NULL),(109,'piece fresh ginger',NULL,NULL,NULL),(110,'apple cider\r',NULL,NULL,NULL),(111,'white vinegar\r',NULL,NULL,NULL),(112,'packed brown sugar\r',NULL,NULL,NULL),(113,'mustard seed\r',NULL,NULL,NULL),(114,'kosher salt\r',NULL,NULL,NULL),(115,'black pepper\r',NULL,NULL,NULL),(116,'dried thyme\r',NULL,NULL,NULL),(117,'ground allspice\r',NULL,NULL,NULL),(118,'ground nutmeg\r',NULL,NULL,NULL),(119,'ground cinnamon\r',NULL,NULL,NULL),(120,'pork tenderloin',NULL,NULL,NULL),(121,'bananas\r',NULL,NULL,NULL),(122,'packets low calorie granulated sugar substitute ',NULL,NULL,NULL),(123,'peeled and cubed sweet potato\r',NULL,NULL,NULL),(124,'coconut oil',NULL,NULL,NULL),(125,'chopped fresh rosemary\r',NULL,NULL,NULL),(126,'chopped fresh thyme\r',NULL,NULL,NULL),(127,'salt and ground black pepper to taste',NULL,NULL,NULL),(128,'dried figs\r',NULL,NULL,NULL),(129,'orange',NULL,NULL,NULL),(130,'semisweet chocolate chips\r',NULL,NULL,NULL),(131,'whiskey\r',NULL,NULL,NULL),(132,'chopped walnuts\r',NULL,NULL,NULL),(133,'cinnamon\r',NULL,NULL,NULL),(134,'maple sugar',NULL,NULL,NULL),(135,'yellow squash',NULL,NULL,NULL),(136,'onion',NULL,NULL,NULL),(137,'condensed cream of chicken soup\r',NULL,NULL,NULL),(138,'container sour cream\r',NULL,NULL,NULL),(139,'shredded Cheddar cheese\r',NULL,NULL,NULL),(140,'chopped pimento peppers\r',NULL,NULL,NULL),(141,'dry Italian-style salad dressing mix',NULL,NULL,NULL);
/*!40000 ALTER TABLE `ingredients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipe_categories`
--

DROP TABLE IF EXISTS `recipe_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recipe_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `recipe_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_recipe_categories_1_idx` (`recipe_id`),
  KEY `fk_recipe_categories_2_idx` (`category_id`),
  CONSTRAINT `fk_recipe_categories_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_recipe_categories_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipe_categories`
--

LOCK TABLES `recipe_categories` WRITE;
/*!40000 ALTER TABLE `recipe_categories` DISABLE KEYS */;
INSERT INTO `recipe_categories` VALUES (1,1,1),(2,31,1),(3,17,2),(4,31,2),(5,12,3),(6,17,3),(7,31,3),(8,12,4),(9,17,4),(10,31,4),(11,12,5),(12,25,5),(13,31,5),(14,21,6),(15,3,7),(16,19,7),(17,30,7),(18,28,8),(19,8,9),(20,11,9),(21,5,10),(22,14,10),(23,18,10),(24,8,12),(25,12,13),(26,31,13),(27,9,14),(28,10,14),(29,20,15),(30,30,15),(31,2,16),(32,22,16),(33,12,17),(34,31,18),(35,9,19),(36,10,19),(37,11,19),(38,31,19),(39,17,20);
/*!40000 ALTER TABLE `recipe_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipe_comments`
--

DROP TABLE IF EXISTS `recipe_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recipe_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `recipe_id` int(11) DEFAULT NULL,
  `comment` varchar(500) NOT NULL,
  `created_at` datetime NOT NULL,
  `flaged` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_recipe_comments_1_idx` (`user_id`),
  KEY `fk_recipe_comments_2_idx` (`recipe_id`),
  CONSTRAINT `fk_recipe_comments_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_recipe_comments_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipe_comments`
--

LOCK TABLES `recipe_comments` WRITE;
/*!40000 ALTER TABLE `recipe_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `recipe_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipe_ingredients`
--

DROP TABLE IF EXISTS `recipe_ingredients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recipe_ingredients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipe_id` int(11) DEFAULT NULL,
  `ingredient_id` int(11) DEFAULT NULL,
  `ammount` varchar(10) NOT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `note` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_recipe_ingredients_1_idx` (`ingredient_id`),
  KEY `fk_recipe_ingredients_2_idx` (`recipe_id`),
  KEY `fk_recipe_ingredients_3_idx` (`unit_id`),
  CONSTRAINT `fk_recipe_ingredients_1` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_recipe_ingredients_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_recipe_ingredients_3` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipe_ingredients`
--

LOCK TABLES `recipe_ingredients` WRITE;
/*!40000 ALTER TABLE `recipe_ingredients` DISABLE KEYS */;
INSERT INTO `recipe_ingredients` VALUES (1,1,1,'2',6,'drained\r'),(2,1,2,'0.33333333',6,NULL),(3,1,3,'0.25',6,NULL),(4,1,4,'1',13,NULL),(5,1,5,'2',16,'halved\r'),(6,1,6,'1',12,NULL),(7,1,7,'1',16,NULL),(8,1,8,'1',13,NULL),(9,2,9,'4',16,'peeled and cubed\r'),(10,2,10,'1.5',12,'(clarified butter)'),(11,2,11,'1',13,NULL),(12,2,12,'2',16,'chopped\r'),(13,2,13,'1',16,'finely chopped\r (1 inch)'),(14,2,14,'1',13,NULL),(15,2,15,'1',13,'ground\r'),(16,2,16,'1',13,'(dried mango powder)'),(17,2,4,'0.5',13,NULL),(18,2,17,'1',16,'chopped'),(19,3,18,'1',22,'(8 ounce)'),(20,3,19,'2',7,NULL),(21,3,20,'4',16,NULL),(22,3,6,'1',12,NULL),(23,3,21,'1',12,NULL),(24,3,22,'1',12,NULL),(25,3,23,'',NULL,NULL),(26,3,24,'',NULL,NULL),(27,3,25,'0.25',6,NULL),(28,4,26,'2',12,NULL),(29,4,27,'2',16,'minced\r'),(30,4,5,'2',16,'minced\r'),(31,4,28,'2',13,'finely chopped\r'),(32,4,29,'6',16,NULL),(33,4,30,'2',16,'crushed\r (2 inch)'),(34,4,31,'1',13,NULL),(35,4,32,'1',13,NULL),(36,4,4,'',NULL,NULL),(37,4,33,'1',13,NULL),(38,4,34,'1',13,NULL),(39,4,35,'2',5,'(15 ounce)'),(40,4,36,'1',6,NULL),(41,5,37,'0.5',6,NULL),(42,5,38,'0.25',13,NULL),(43,5,39,'0.25',13,NULL),(44,5,40,'0.125',13,NULL),(45,5,31,'0.25',13,NULL),(46,5,41,'2',5,'rinsed and drained\r (15 ounce)'),(47,5,42,'2',5,'drained\r (15 ounce)'),(48,5,43,'0.5',6,NULL),(49,5,44,'0.5',6,NULL),(50,5,45,'0.5',6,'chopped'),(51,6,46,'2',7,NULL),(52,6,27,'2',16,'minced\r'),(53,6,5,'4',16,'minced\r'),(54,6,47,'0.75',6,NULL),(55,6,48,'1.5',13,NULL),(56,6,49,'2',12,NULL),(57,6,50,'1',6,NULL),(58,6,51,'1.5',6,NULL),(59,6,52,'2',5,'(28 ounce)'),(60,6,22,'1',12,NULL),(61,6,53,'1',7,NULL),(62,6,25,'0.5',6,NULL),(63,7,54,'1',7,NULL),(64,7,55,'',NULL,NULL),(65,7,56,'3',5,'(15 ounce)'),(66,7,57,'3',5,'(14.5 ounce)'),(67,7,58,'2',16,'chopped\r'),(68,7,45,'1',16,'chopped\r'),(69,7,59,'0.25',6,NULL),(70,7,14,'2',12,NULL),(71,7,31,'1',13,NULL),(72,7,60,'1',13,NULL),(73,7,48,'1',13,NULL),(74,7,61,'1',16,NULL),(75,7,62,'0.5',6,NULL),(76,8,63,'2',12,NULL),(77,8,43,'2',12,NULL),(78,8,64,'1.5',6,NULL),(79,8,65,'2',6,NULL),(80,8,66,'1',6,NULL),(81,9,67,'6',16,'(skinless)'),(82,9,55,'',NULL,NULL),(83,9,68,'0.5',6,NULL),(84,9,69,'0.5',6,NULL),(85,9,48,'1',13,NULL),(86,9,70,'1',13,NULL),(87,9,71,'0.5',13,NULL),(88,10,72,'2.25',13,NULL),(89,10,73,'0.5',13,NULL),(90,10,74,'1.5',6,'(110 degrees F/45 degrees C)'),(91,10,4,'1',13,NULL),(92,10,6,'2',12,NULL),(93,10,75,'3.33333333',6,NULL),(94,11,76,'2.25',6,NULL),(95,11,77,'2',19,NULL),(96,11,78,'1.5',7,NULL),(97,11,27,'2',16,'thinly sliced\r'),(98,11,4,'0.5',13,NULL),(99,11,79,'0.25',13,NULL),(100,11,80,'1',16,NULL),(101,11,58,'3',16,'chopped\r'),(102,11,81,'3',16,'chopped\r'),(103,11,82,'1',16,'diced'),(104,12,67,'6',16,'(skinless)'),(105,12,83,'1.5',12,NULL),(106,12,84,'2',12,NULL),(107,12,85,'1.5',13,NULL),(108,12,86,'0.25',6,NULL),(109,12,87,'2',13,NULL),(110,13,6,'2',12,NULL),(111,13,88,'3',12,NULL),(112,13,89,'3',16,'cut into 1 inch pieces\r'),(113,13,90,'1',16,'chopped\r'),(114,13,5,'3',16,'finely chopped\r'),(115,13,91,'8',6,NULL),(116,13,92,'',NULL,NULL),(117,13,93,'1',6,'(optional)'),(118,14,94,'1.5',13,NULL),(119,14,95,'1',6,'(such as Splenda®)'),(120,14,96,'6',16,NULL),(121,14,97,'0.25',13,NULL),(122,14,98,'0.25',13,NULL),(123,15,99,'4',12,NULL),(124,15,100,'2',6,NULL),(125,15,101,'1',7,'sliced\r'),(126,15,102,'2',13,NULL),(127,15,70,'1',12,NULL),(128,15,86,'1',12,NULL),(129,15,65,'2',6,NULL),(130,15,50,'1',6,NULL),(131,15,103,'3',12,NULL),(132,15,4,'1',13,NULL),(133,15,24,'',NULL,NULL),(134,15,3,'2',13,NULL),(135,15,104,'0.25',6,NULL),(136,15,105,'0.5',6,NULL),(137,16,106,'2',16,'seeded\r'),(138,16,107,'1',16,'chopped\r'),(139,16,108,'2',16,'chopped\r'),(140,16,109,'1',16,'peeled and thinly sliced\r (1 inch)'),(141,16,5,'3',16,'peeled\r'),(142,16,110,'0.25',6,NULL),(143,16,111,'0.25',6,NULL),(144,16,86,'3',12,NULL),(145,16,6,'2',12,NULL),(146,16,112,'1.5',12,NULL),(147,16,113,'0.75',13,NULL),(148,16,114,'1',12,NULL),(149,16,115,'1',13,NULL),(150,16,116,'1',12,NULL),(151,16,117,'1',12,NULL),(152,16,118,'1',13,NULL),(153,16,119,'0.5',13,NULL),(154,16,120,'2.5',7,'butterflied and pounded to 3/4 inch'),(155,17,50,'1',6,NULL),(156,17,121,'1.5',16,NULL),(157,17,122,'5',16,'(such as Sweet \'n Low®)(1 gram)'),(158,18,123,'1',6,NULL),(159,18,124,'0.5',13,'melted\r'),(160,18,125,'1.5',13,NULL),(161,18,126,'1.5',13,NULL),(162,18,127,'',NULL,NULL),(163,19,128,'1',7,NULL),(164,19,129,'1',16,'zested\r'),(165,19,130,'0.5',6,NULL),(166,19,131,'0.25',6,NULL),(167,19,132,'0.5',6,NULL),(168,19,133,'1',13,NULL),(169,19,134,'0.25',6,NULL),(170,20,135,'2',7,'chopped\r'),(171,20,136,'1',16,'chopped\r'),(172,20,137,'1',5,'(10.75 ounce)'),(173,20,138,'1',16,'(8 ounce)'),(174,20,88,'4',12,NULL),(175,20,47,'1',6,NULL),(176,20,139,'1',6,NULL),(177,20,140,'2',12,NULL),(178,20,55,'',NULL,NULL),(179,20,141,'1',22,'(.7 ounce)');
/*!40000 ALTER TABLE `recipe_ingredients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipe_photos`
--

DROP TABLE IF EXISTS `recipe_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recipe_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipe_id` int(11) DEFAULT NULL,
  `url` varchar(2083) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `uploaded_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_recipe_photos_1_idx` (`recipe_id`),
  KEY `fk_recipe_photos_2_idx` (`user_id`),
  CONSTRAINT `fk_recipe_photos_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_recipe_photos_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipe_photos`
--

LOCK TABLES `recipe_photos` WRITE;
/*!40000 ALTER TABLE `recipe_photos` DISABLE KEYS */;
INSERT INTO `recipe_photos` VALUES (1,1,'images/dishes/1-c3eac45ce0b475d5555cc53901798f1d1bf1d4cd.jpeg',1,'2014-04-15 23:14:31'),(2,2,'images/dishes/2-9c21f1f2591b2e7f3f33f1d3a665d79e392957b8.jpeg',1,'2014-04-16 00:00:58'),(3,3,'images/dishes/3-2096534883534da1889db1d.jpeg',1,'2014-04-16 00:15:52'),(4,4,'images/dishes/4-534da5ba53a8a.jpeg',1,'2014-04-16 00:33:46'),(5,5,'images/dishes/5-534da6c1c9dc4.jpeg',1,'2014-04-16 00:38:09'),(6,6,'images/dishes/6-534da7d49239c.jpeg',1,'2014-04-16 00:42:44'),(7,7,'images/dishes/7-534da87cd8cf9.jpeg',1,'2014-04-16 00:45:33'),(8,8,'images/dishes/8-534da93dbeec6.jpeg',1,'2014-04-16 00:48:45'),(9,9,'images/dishes/9-534da9ec41e45.jpeg',1,'2014-04-16 00:51:40'),(10,10,'images/dishes/10-534daab8dce41.jpeg',1,'2014-04-16 00:55:05'),(11,11,'images/dishes/11-534dab98a95aa.jpeg',1,'2014-04-16 00:58:48'),(12,12,'images/dishes/12-534dac7ab37cb.jpeg',1,'2014-04-16 01:02:34'),(13,13,'images/dishes/13-534dad3015290.jpeg',1,'2014-04-16 01:05:36'),(14,14,'images/dishes/14-534dadec80f42.jpeg',1,'2014-04-16 01:08:44'),(15,15,'images/dishes/15-534dae72a0cf0.jpeg',1,'2014-04-16 01:10:58'),(16,16,'images/dishes/16-534daf1cdfebf.jpeg',1,'2014-04-16 01:13:49'),(17,17,'images/dishes/17-534dafa00c474.jpeg',1,'2014-04-16 01:16:00'),(18,18,'images/dishes/18-534db01f6fb13.jpeg',1,'2014-04-16 01:18:07'),(19,19,'images/dishes/19-534db0d69aebc.jpeg',1,'2014-04-16 01:21:10'),(20,20,'images/dishes/20-534db19c7c912.jpeg',1,'2014-04-16 01:24:28');
/*!40000 ALTER TABLE `recipe_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipe_ratings`
--

DROP TABLE IF EXISTS `recipe_ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recipe_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipe_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` tinyint(1) NOT NULL,
  `rated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8252098959D8A214` (`recipe_id`),
  KEY `IDX_82520989A76ED395` (`user_id`),
  CONSTRAINT `FK_82520989A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_8252098959D8A214` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipe_ratings`
--

LOCK TABLES `recipe_ratings` WRITE;
/*!40000 ALTER TABLE `recipe_ratings` DISABLE KEYS */;
/*!40000 ALTER TABLE `recipe_ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipes`
--

DROP TABLE IF EXISTS `recipes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recipes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `calories` int(11) NOT NULL,
  `prep_time` time DEFAULT NULL,
  `cook_time` time DEFAULT NULL,
  `ready_time` time DEFAULT NULL,
  `servings` smallint(6) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cover_photo_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `private` tinyint(1) DEFAULT NULL,
  `approved` tinyint(1) DEFAULT NULL,
  `rating` double DEFAULT NULL,
  `carbs` double NOT NULL,
  `fat` double NOT NULL,
  `protein` double NOT NULL,
  `directions` text,
  `about` longtext,
  PRIMARY KEY (`id`),
  KEY `fk_recipes_2_idx` (`cover_photo_id`),
  KEY `fk_recipes_1_idx` (`user_id`),
  CONSTRAINT `fk_recipes_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_recipes_2` FOREIGN KEY (`cover_photo_id`) REFERENCES `recipe_photos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipes`
--

LOCK TABLES `recipes` WRITE;
/*!40000 ALTER TABLE `recipes` DISABLE KEYS */;
INSERT INTO `recipes` VALUES (1,'Hummus III',77,'00:10:00','00:00:00','00:10:00',2,1,1,'2014-04-15 23:14:26',0,NULL,NULL,8.1,4.3,2.6,'Place the garbanzo beans, tahini, lemon juice, salt and garlic in a blender or food processor. Blend until smooth. Transfer mixture to a serving bowl.\r\nDrizzle olive oil over the garbanzo bean mixture. Sprinkle with paprika and parsley.','Hummus is a pureed garbanzo bean dip with Middle Eastern origins. Serve with pita and an assortment of fresh vegetables. This is the secret combination straight from a Boston restaurant. Tahini, or sesame seed paste, can be found in health food stores, gourmet shops and even many grocery stores.'),(2,'Best Potatoes Ever!',352,'00:15:00','00:30:00','00:45:00',4,1,2,'2014-04-16 00:00:57',0,NULL,NULL,71,5.6,7.2,'Place the potatoes in a saucepan with enough water to cover. Bring to a boil, and cook 10 minutes, until tender. Drain and allow to cool slightly.\r\nHeat the ghee in a large skillet over medium heat. Lightly toast the cumin seeds in the ghee. Mix in the green chile peppers and ginger. Season with chili powder and coriander. Stir in the potatoes, and cook about 5 minutes. Season with amchoor and salt, and continue cooking about 15 minutes. Garnish with cilantro to serve.','These Indian potatoes taste awesome for lunch or dinner with chipatis, rotis, or wheat tortillas. The spices can be found at any Indian grocery store.'),(3,'Tomato and Garlic Pasta',260,'00:00:00','00:00:00','00:00:00',4,1,3,'2014-04-16 00:15:48',0,NULL,NULL,41.9,6.8,10.3,'Place tomatoes in a kettle, and cover with cold water. Bring just to the boil. Pour off water, and cover again with cold water. Peel. Cut into small pieces.\r\nCook the pasta in a large pot of boiling salted water until al dente.\r\nIn a large skillet or saute pan, saute the garlic in enough olive oil to cover the bottom of the pan. The garlic should just become opaque, not brown. Stir in the tomato paste. Immediately stir in the tomatoes, and salt and pepper. Reduce heat, and simmer until the pasta is ready; add the basil.\r\nDrain the pasta, but do not rinse in cold water. Toss with a couple of tablespoons of olive oil, and then mix into the sauce. Reduce the heat as low as possible. Keep warm, uncovered, for about 10 minutes when it is ready to serve. Garnish generously with fresh Parmesan cheese.\r\nVARIATIONS: Saute fresh quartered mushrooms with the garlic, or add shoestring zucchini along with the tomato.','There is nothing nicer than the flavor of fresh tomatoes. You can use canned, but the trouble you take to prepare this dish is worth it. You prepare the sauce while the pasta is cooking, no long hours of waiting. Great if you want meatless pasta.'),(4,'Chickpea Curry',135,'00:10:00','00:30:00','00:40:00',8,1,4,'2014-04-16 00:33:46',0,NULL,NULL,20.5,4.5,4.1,'Heat oil in a large frying pan over medium heat, and fry onions until tender.\r\nStir in garlic, ginger, cloves, cinnamon, cumin, coriander, salt, cayenne, and turmeric. Cook for 1 minute over medium heat, stirring constantly. Mix in garbanzo beans and their liquid. Continue to cook and stir until all ingredients are well blended and heated through. Remove from heat. Stir in cilantro just before serving, reserving 1 tablespoon for garnish.','We usually recommend preparing the beans at home, but using canned chickpeas allows for a fast, convenient dish.'),(5,'Black Bean and Corn Salad I',304,'00:15:00','00:00:00','00:15:00',6,1,5,'2014-04-16 00:38:09',0,NULL,NULL,49.5,8.5,11.7,'In a small bowl, mix together vinaigrette, seasoned pepper, cilantro, cayenne pepper, and cumin. Set dressing aside.\r\nIn a large bowl, stir together beans, corn, onion, green onions, and red bell pepper. Toss with dressing. Cover, and refrigerate overnight. Toss again before serving.','This bright, simple salad is a great pot luck dish, or a great main dish served with tortillas or cornbread. Depending on your family\'s tastes, red peppers can be all sweet bell peppers, or can be a combination of sweet and hot peppers. Keeps well for several days.'),(6,'Turkey Bolognese Sauce',510,'00:10:00','03:10:00','03:20:00',8,1,6,'2014-04-16 00:42:44',0,NULL,NULL,58,12.6,32.4,'In large saucepan over medium heat, cook turkey, onion, garlic, carrot, basil and jalapeno until turkey is brown. Pour in milk, reduce heat to low, and simmer until reduced by one-third. Stir in wine and reduce again. Pour in tomatoes and tomato paste and simmer 3 hours more.\r\nBring a large pot of lightly salted water to a boil. Add pasta and cook for 8 to 10 minutes or until al dente; drain. Toss with tomato sauce and top with Parmesan. Serve.','Delicious ground turkey meat recipe. Excellent sauce.'),(7,'The Ultimate Chili',414,'00:10:00','06:10:00','06:20:00',6,1,7,'2014-04-16 00:45:32',0,NULL,NULL,49.5,11,28.4,'In a large skillet over medium-high heat, cook ground beef until evenly browned. Drain off grease, and season to taste with salt and pepper.\r\nIn a slow cooker, combine the cooked beef, kidney beans, tomatoes, celery, red bell pepper, and red wine vinegar. Season with chili powder, cumin, parsley, basil and Worcestershire sauce. Stir to distribute ingredients evenly.\r\nCook on High for 6 hours, or on Low for 8 hours. Pour in the wine during the last 2 hours.','Easy recipe with little preparation time. This can also be made with ground turkey, and it tastes even better the next day!'),(8,'Best Spanish Rice',286,'00:10:00','00:20:00','00:30:00',5,1,8,'2014-04-16 00:48:45',0,NULL,NULL,50.9,6.5,5.7,'Heat oil in a large, heavy skillet over medium heat. Stir in onion, and cook until tender, about 5 minutes.\r\nMix rice into skillet, stirring often. When rice begins to brown, stir in chicken broth and salsa. Reduce heat, cover and simmer 20 minutes, until liquid has been absorbed.','The combination of picante sauce and chicken broth makes this easy recipe very tasty!'),(9,'Baked Honey Mustard Chicken',232,'00:15:00','00:45:00','01:00:00',6,1,9,'2014-04-16 00:51:40',0,NULL,NULL,24.8,3.7,25.6,'Preheat oven to 350 degrees F (175 degrees C).\r\nSprinkle chicken breasts with salt and pepper to taste, and place in a lightly greased 9x13 inch baking dish. In a small bowl, combine the honey, mustard, basil, paprika, and parsley. Mix well. Pour 1/2 of this mixture over the chicken, and brush to cover.\r\nBake in the preheated oven for 30 minutes. Turn chicken pieces over and brush with the remaining 1/2 of the honey mustard mixture. Bake for an additional 10 to 15 minutes, or until chicken is no longer pink and juices run clear. Let cool 10 minutes before serving.','Quick and easy to prepare, and the kids love it too!'),(10,'Jay\'s Signature Pizza Crust',119,'00:30:00','00:20:00','01:50:00',1,1,10,'2014-04-16 00:55:04',0,NULL,NULL,21.6,2.1,3.1,'In a large bowl, dissolve the yeast and brown sugar in the water, and let sit for 10 minutes.\r\nStir the salt and oil into the yeast solution. Mix in 2 1/2 cups of the flour.\r\nTurn dough out onto a clean, well floured surface, and knead in more flour until the dough is no longer sticky. Place the dough into a well oiled bowl, and cover with a cloth. Let the dough rise until double; this should take about 1 hour. Punch down the dough, and form a tight ball. Allow the dough to relax for a minute before rolling out. Use for your favorite pizza recipe.\r\nPreheat oven to 425 degrees F (220 degrees C). If you are baking the dough on a pizza stone, you may place your toppings on the dough, and bake immediately. If you are baking your pizza in a pan, lightly oil the pan, and let the dough rise for 15 or 20 minutes before topping and baking it.\r\nBake pizza in preheated oven, until the cheese and crust are golden brown, about 15 to 20 minutes.','This recipe yields a crust that is soft and doughy on the inside and slightly crusty on the outside. Cover it with your favorite sauce and topping to make a delicious pizza.'),(11,'Split Pea Soup',310,'00:15:00','02:00:00','03:20:00',6,1,11,'2014-04-16 00:58:48',0,NULL,NULL,57.9,1,19.7,'In a large stock pot, cover peas with 2 quarts cold water and soak overnight. If you need a faster method, simmer the peas gently for 2 minutes, and then soak for l hour.\r\nOnce peas are soaked, add ham bone, onion, salt, pepper and marjoram. Cover, bring to boil and then simmer for 1 1/2 hours, stirring occasionally.\r\nRemove bone; cut off meat, dice and return meat to soup. Add celery, carrots and potatoes. Cook slowly, uncovered for 30 to 40 minutes, or until vegetables are tender.','This is a wonderful, hearty split pea soup. Great for a fall or blustery winter day.'),(12,'Low-Cal Chicken',177,'00:00:00','00:00:00','00:00:00',5,1,12,'2014-04-16 01:02:34',0,NULL,NULL,2.6,1.8,35.7,'Preheat oven to 425 degrees F (220 degrees C).\r\nPlace chicken in a 9x13 inch baking dish; sprinkle with onion, garlic, seasoning, soy sauce and sweetener.\r\nPlace foil over pan and bake for one hour at 425 degrees F (220 degrees C). It\'s ready to serve!','An easy, healthy chicken dish with a special sweet and sour tang! Goes great with rice and salad.'),(13,'Low Carb Cauliflower Leek Soup',155,'00:15:00','00:01:00','00:16:00',12,1,13,'2014-04-16 01:05:35',0,NULL,NULL,8.3,13.1,2.4,'Heat the olive oil and butter in a large pot over medium heat, and saute the leeks, cauliflower, and garlic for about 10 minutes. Stir in the vegetable broth, and bring the mixture to a boil. Reduce heat, cover, and simmer 45 minutes.\r\nRemove the soup from heat. Blend the soup with an immersion blender or hand mixer. Season with salt and pepper. Mix in the heavy cream, and continue blending until smooth.','A simple yet tasty alternative to potato leek soup. Great for those watching their carbs or calories, or just looking for a different vegetarian soup.'),(14,'Low Carb Flavored Meringue Cookies',8,'00:15:00','01:30:00','01:45:00',48,1,14,'2014-04-16 01:08:44',0,NULL,NULL,1,0,1,'Preheat oven to 250 degrees F (120 degrees C). Line 2 baking sheets with parchment paper.\r\nCut about 1/4 inch off a corner of a heavy gallon-size resealable plastic bag, and push a large-size cake decorating tip (such as a star tip) into the opening. The fit should be tight.\r\nIn a small bowl, stir the gelatin mix with the sugar substitute. In a large bowl, using an electric mixer, beat the egg whites with cream of tartar and salt until stiff peaks form. As you beat the egg whites, gradually add the gelatin mixture, about 1 tablespoon at a time. Spoon the fluffy mixture into the prepared plastic bag, and gently squeeze and twist the bag to force the meringue mixture to the decorating tip. (Do not seal bag, so that air can escape.)\r\nSqueeze the bag to place golf-ball size dollops of meringue mixture onto the prepared baking sheets. For a decorative effect, twist and lift as you place the cookie on the sheet, to make a pretty shape.\r\nBake in the preheated oven until the cookies are set and dry, about 1 hour and 30 minutes. Do not open oven door while baking. At end of baking time, turn off oven, open oven door, and allow the cookies to slowly cool in the oven before removing from baking sheets. Store in airtight container.','A very light and airy low carb treat that satisfies any sweet tooth. You are able to change the flavor to suit your taste. The secret is sugar-free gelatin! This is an easy recipe and they bake up beautiful every time. My son enjoys strawberry. He says they taste like cotton candy!'),(15,'Hungarian Mushroom Soup',201,'00:15:00','00:35:00','00:50:00',6,1,15,'2014-04-16 01:10:58',0,NULL,NULL,14.8,13.5,7.5,'Melt the butter in a large pot over medium heat. Saute the onions in the butter for 5 minutes. Add the mushrooms and saute for 5 more minutes. Stir in the dill, paprika, soy sauce and broth. Reduce heat to low, cover, and simmer for 15 minutes.\r\nIn a separate small bowl, whisk the milk and flour together. Pour this into the soup and stir well to blend. Cover and simmer for 15 more minutes, stirring occasionally.\r\nFinally, stir in the salt, ground black pepper, lemon juice, parsley and sour cream. Mix together and allow to heat through over low heat, about 3 to 5 minutes. Do not boil. Serve immediately.','My family loves soup and this is one of their favorites. It has lots of flavor and is fairly quick to make. It\'s primarily a mushroom soup but derives a lot of its flavor from other ingredients.'),(16,'Grilled Jerk Pork Tenderloin',248,'00:30:00','00:15:00','08:45:00',8,1,16,'2014-04-16 01:13:48',0,NULL,NULL,11,10.7,26.9,'Combine habanero peppers, onion, green onions, ginger, and garlic, in a blender or food processor. Blend until quite fine. Add cider, white vinegar, soy sauce, olive oil, and sugar. Season with mustard seed, salt, pepper, thyme, allspice, nutmeg, and cinnamon. Continue to blend until smooth.\r\nPlace the pork tenderloin in a shallow casserole dish; pour jerk marinade over, and massage into the meat to ensure all of the pork is coated. Cover and refrigerate for 8 hours.\r\nPreheat grill for medium-high heat.\r\nLightly oil grate. Grill slabs of tenderloin over hot coals, turning as needed to keep from burning but allowing nice grill marks and browning. about 6 to 7 minutes on each side, or until the internal temperature has reached 145 degrees F (63 degrees C). Remove meat from grill; slice thinly and serve.','I feel that the apple cider makes this Jerk marinade unique, and a bit untraditional. Being from the apple country of Virginia the apple cider was a natural. For added flavor, add apple or hickory wood chips soaked in water on top of the hot coals. You can begin grilling when the chips start smoking. Grilled pineapple goes well with this as a side dish.'),(17,'Milk Banana Smoothie',280,'00:05:00','00:00:00','00:05:00',1,1,17,'2014-04-16 01:15:59',0,NULL,NULL,56.8,5.4,10,'Blend milk, bananas, and sugar substitute in a blender or food processor until smooth.','Low fat, no-sugar-added milk banana smoothie.'),(18,'Low-Cal Roasted Sweet Potato Bites',137,'00:10:00','00:20:00','00:30:00',1,1,18,'2014-04-16 01:18:07',0,NULL,NULL,27.2,2.5,2.2,'Preheat oven to 375 degrees F (190 degrees C).\r\nPlace sweet potato cubes in a bowl. Drizzle coconut oil over potatoes and toss, using your hands, until each cube is coated. Spread sweet potato cubes onto a baking sheet; season with rosemary, thyme, salt, and pepper.\r\nBake in the preheated oven until potatoes are softened, about 20 minutes.','We discovered this method looking for healthy alternatives to our usual Christmas/Thanksgiving side dishes. They are so good and filling! This is great as a breakfast hash brown substitute or a side dish to dinner. Mix with other root vegetables to create a more colorful (and delicious) plate, but it will change calorie count.'),(19,'Fig Filling for Pastry',95,'00:25:00','00:05:00','00:30:00',10,1,19,'2014-04-16 01:21:10',0,NULL,NULL,16.9,2.9,1.1,'Remove stems from figs with scissors. Chop in food processor in batches.\r\nIn a non-stick pan, combine chopped figs with orange zest, chocolate chips, whiskey, walnuts, maple syrup, and cinnamon. Heat over medium heat until chocolate melts, stirring frequently. Cool completely.','This recipe is the traditional filling used at Christmas time as a filling for Italian cookies but surely could be used for filling puff pastry. Leftovers can be stored in refrigerator in tightly covered container. This recipe can easily be doubled.'),(20,'Granny\'s Squash Casserole',194,'00:30:00','00:45:00','01:15:00',10,1,20,'2014-04-16 01:24:28',0,NULL,NULL,10.2,15.1,5.4,'Preheat oven to 350 degrees F (175 degrees C).\r\nBring a pot of salted water to a boil. Add squash and onion; cook until tender but still firm. Drain.\r\nIn a large bowl, mix the squash, onion, cream of chicken soup, sour cream, butter, grated carrots, Cheddar cheese, pimento peppers, salt and pepper.\r\nPour mixture into a medium baking dish and sprinkle the top with the Italian-style salad dressing mix.\r\nBake at 350 degrees F (175 degrees C) for 45 minutes.','This one has been passed down for generations! Tender squash is baked with a variety of sumptuous flavors to create a veritable cornucopia of delight.');
/*!40000 ALTER TABLE `recipes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `main` varchar(10) NOT NULL,
  `plural` varchar(45) DEFAULT NULL,
  `system` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES (1,'g','g','metric'),(2,'kg','kg','metric'),(3,'gram','grams','metric'),(4,'kilogram','kilograms','metric'),(5,'can','cans','uni'),(6,'cup','cups','us'),(7,'pound','pounds','us'),(8,'cube','cubes','uni'),(9,'ounce','ounces','us'),(10,'liter','liters','metric'),(11,'litre','litres','metric'),(12,'tablespoon','tablespoons','us'),(13,'teaspoon','teaspoons','us'),(14,'tbsp','tbsps','us'),(15,'tsp','tsps','us'),(16,'unit','units','uni'),(17,'jar','jars','uni'),(18,'gallon','gallons','us'),(19,'quart','quarts','us'),(20,'degree F.','degrees F.','us'),(21,'degree C.','degrees C.','metric'),(22,'package','packages','uni');
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_favorites`
--

DROP TABLE IF EXISTS `user_favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `recipe_id` int(11) DEFAULT NULL,
  `added_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_favorites_1_idx` (`user_id`),
  KEY `fk_user_favorites_2_idx` (`recipe_id`),
  CONSTRAINT `fk_user_favorites_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_favorites_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_favorites`
--

LOCK TABLES `user_favorites` WRITE;
/*!40000 ALTER TABLE `user_favorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `username_canonical` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_canonical` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1483A5E992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_1483A5E9A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='FOSUserBundle';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin','admin@localhost','admin@localhost',1,'8nm6o9l7zpk4gkkgsogo80sk0w88sss','UmrqnngE2rZGapoqR2biwily5iYJwoavC+FdfLBLCMBIEdHQ5NQPQxAGaDu/hXGTSf7RPtrDEH22RXBz7VpFfQ==','2014-04-15 22:45:02',0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-04-16  2:04:13
