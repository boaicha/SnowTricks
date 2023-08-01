<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230728103127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discussion (id INT AUTO_INCREMENT NOT NULL, iduser_id INT NOT NULL, id_trick_id INT NOT NULL, creation_date DATE NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_C0B9F90F786A81FB (iduser_id), INDEX IDX_C0B9F90FE25A52BB (id_trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, id_trick_id INT NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_C53D045FE25A52BB (id_trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trick (id INT AUTO_INCREMENT NOT NULL, id_category_id INT NOT NULL, id_user_id INT NOT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, creation_date DATE NOT NULL, modification_date DATE DEFAULT NULL, INDEX IDX_D8F0A91EA545015 (id_category_id), INDEX IDX_D8F0A91E79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, first_name VARCHAR(50) NOT NULL, mail VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, profil_photo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, id_trick_id INT NOT NULL, video VARCHAR(255) NOT NULL, INDEX IDX_7CC7DA2CE25A52BB (id_trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE discussion ADD CONSTRAINT FK_C0B9F90F786A81FB FOREIGN KEY (iduser_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE discussion ADD CONSTRAINT FK_C0B9F90FE25A52BB FOREIGN KEY (id_trick_id) REFERENCES trick (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FE25A52BB FOREIGN KEY (id_trick_id) REFERENCES trick (id)');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EA545015 FOREIGN KEY (id_category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E79F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CE25A52BB FOREIGN KEY (id_trick_id) REFERENCES trick (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discussion DROP FOREIGN KEY FK_C0B9F90F786A81FB');
        $this->addSql('ALTER TABLE discussion DROP FOREIGN KEY FK_C0B9F90FE25A52BB');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FE25A52BB');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91EA545015');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E79F37AE5');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2CE25A52BB');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE discussion');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE trick');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE video');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
