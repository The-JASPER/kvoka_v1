<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241211220948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, entry_id INT NOT NULL, filename VARCHAR(255) NOT NULL, INDEX IDX_C53D045FBA364942 (entry_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FBA364942 FOREIGN KEY (entry_id) REFERENCES entry (id)');
        $this->addSql('ALTER TABLE entry ADD color_id INT NOT NULL, ADD shape_id INT NOT NULL, DROP text, DROP email, DROP color, DROP shape, DROP images');
        $this->addSql('ALTER TABLE entry ADD CONSTRAINT FK_2B219D707ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id)');
        $this->addSql('ALTER TABLE entry ADD CONSTRAINT FK_2B219D7050266CBB FOREIGN KEY (shape_id) REFERENCES shape (id)');
        $this->addSql('CREATE INDEX IDX_2B219D707ADA1FB5 ON entry (color_id)');
        $this->addSql('CREATE INDEX IDX_2B219D7050266CBB ON entry (shape_id)');
        $this->addSql('ALTER TABLE user RENAME INDEX username TO UNIQ_8D93D649F85E0677');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FBA364942');
        $this->addSql('DROP TABLE image');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649f85e0677 TO username');
        $this->addSql('ALTER TABLE entry DROP FOREIGN KEY FK_2B219D707ADA1FB5');
        $this->addSql('ALTER TABLE entry DROP FOREIGN KEY FK_2B219D7050266CBB');
        $this->addSql('DROP INDEX IDX_2B219D707ADA1FB5 ON entry');
        $this->addSql('DROP INDEX IDX_2B219D7050266CBB ON entry');
        $this->addSql('ALTER TABLE entry ADD text VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD color VARCHAR(50) NOT NULL, ADD shape VARCHAR(50) NOT NULL, ADD images JSON NOT NULL, DROP color_id, DROP shape_id');
    }
}
