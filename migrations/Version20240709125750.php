<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240709125750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE album (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE musique (id INT AUTO_INCREMENT NOT NULL, interprete_id INT DEFAULT NULL, album_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, temps DOUBLE PRECISION NOT NULL, INDEX IDX_EE1D56BCF625BF53 (interprete_id), INDEX IDX_EE1D56BC1137ABCF (album_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE style (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE style_musique (style_id INT NOT NULL, musique_id INT NOT NULL, INDEX IDX_8831C888BACD6074 (style_id), INDEX IDX_8831C88825E254A1 (musique_id), PRIMARY KEY(style_id, musique_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE musique ADD CONSTRAINT FK_EE1D56BCF625BF53 FOREIGN KEY (interprete_id) REFERENCES interprete (id)');
        $this->addSql('ALTER TABLE musique ADD CONSTRAINT FK_EE1D56BC1137ABCF FOREIGN KEY (album_id) REFERENCES album (id)');
        $this->addSql('ALTER TABLE style_musique ADD CONSTRAINT FK_8831C888BACD6074 FOREIGN KEY (style_id) REFERENCES style (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE style_musique ADD CONSTRAINT FK_8831C88825E254A1 FOREIGN KEY (musique_id) REFERENCES musique (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE musique DROP FOREIGN KEY FK_EE1D56BCF625BF53');
        $this->addSql('ALTER TABLE musique DROP FOREIGN KEY FK_EE1D56BC1137ABCF');
        $this->addSql('ALTER TABLE style_musique DROP FOREIGN KEY FK_8831C888BACD6074');
        $this->addSql('ALTER TABLE style_musique DROP FOREIGN KEY FK_8831C88825E254A1');
        $this->addSql('DROP TABLE album');
        $this->addSql('DROP TABLE musique');
        $this->addSql('DROP TABLE style');
        $this->addSql('DROP TABLE style_musique');
    }
}
