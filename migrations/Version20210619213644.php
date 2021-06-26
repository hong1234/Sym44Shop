<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210619213644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, card_type VARCHAR(255) NOT NULL, card_number VARCHAR(255) NOT NULL, expiry_date DATE NOT NULL, security_code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_BA388B79395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart_item (id INT AUTO_INCREMENT NOT NULL, cart_id INT DEFAULT NULL, product_id INT DEFAULT NULL, qty INT NOT NULL, unit_price NUMERIC(10, 4) NOT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, INDEX IDX_F0FE25271AD5CDBF (cart_id), UNIQUE INDEX UNIQ_F0FE25274584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, url_key VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_64C19C1DFAB7B3B (url_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, company VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, postcode VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_81398E09E7927C74 (email), UNIQUE INDEX UNIQ_81398E09F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, price NUMERIC(10, 2) NOT NULL, sku VARCHAR(255) NOT NULL, url_key VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, qty INT NOT NULL, image VARCHAR(255) DEFAULT NULL, onsale TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_D34A04ADF9038C4 (sku), UNIQUE INDEX UNIQ_D34A04ADDFAB7B3B (url_key), INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sales_order (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, items_price NUMERIC(10, 4) NOT NULL, shipment_price NUMERIC(10, 4) NOT NULL, total_price NUMERIC(10, 4) NOT NULL, status VARCHAR(255) NOT NULL, payment_method VARCHAR(255) NOT NULL, shipment_method VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, customer_email VARCHAR(255) NOT NULL, customer_first_name VARCHAR(255) NOT NULL, customer_last_name VARCHAR(255) NOT NULL, address_first_name VARCHAR(255) NOT NULL, address_last_name VARCHAR(255) NOT NULL, address_country VARCHAR(255) NOT NULL, address_state VARCHAR(255) DEFAULT NULL, address_city VARCHAR(255) NOT NULL, address_postcode VARCHAR(255) NOT NULL, address_street VARCHAR(255) NOT NULL, address_telephone VARCHAR(255) NOT NULL, INDEX IDX_36D222E9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sales_order_item (id INT AUTO_INCREMENT NOT NULL, sales_order_id INT DEFAULT NULL, product_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, qty INT NOT NULL, unit_price NUMERIC(10, 4) NOT NULL, total_price NUMERIC(10, 4) NOT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, INDEX IDX_5DD6A865C023F51C (sales_order_id), INDEX IDX_5DD6A8654584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B79395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25271AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25274584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE sales_order ADD CONSTRAINT FK_36D222E9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE sales_order_item ADD CONSTRAINT FK_5DD6A865C023F51C FOREIGN KEY (sales_order_id) REFERENCES sales_order (id)');
        $this->addSql('ALTER TABLE sales_order_item ADD CONSTRAINT FK_5DD6A8654584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25271AD5CDBF');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B79395C3F3');
        $this->addSql('ALTER TABLE sales_order DROP FOREIGN KEY FK_36D222E9395C3F3');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25274584665A');
        $this->addSql('ALTER TABLE sales_order_item DROP FOREIGN KEY FK_5DD6A8654584665A');
        $this->addSql('ALTER TABLE sales_order_item DROP FOREIGN KEY FK_5DD6A865C023F51C');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE sales_order');
        $this->addSql('DROP TABLE sales_order_item');
    }
}
