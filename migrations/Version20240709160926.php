<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240709160926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album DROP FOREIGN KEY FK_39986E433DA5256D');
        $this->addSql('DROP INDEX IDX_39986E433DA5256D ON album');
        $this->addSql('ALTER TABLE album DROP image_id');
        $this->addSql('ALTER TABLE image ADD album_id INT DEFAULT NULL, CHANGE path name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F1137ABCF FOREIGN KEY (album_id) REFERENCES album (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C53D045F1137ABCF ON image (album_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E433DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_39986E433DA5256D ON album (image_id)');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F1137ABCF');
        $this->addSql('DROP INDEX UNIQ_C53D045F1137ABCF ON image');
        $this->addSql('ALTER TABLE image DROP album_id, CHANGE name path VARCHAR(255) NOT NULL');
    }
}
