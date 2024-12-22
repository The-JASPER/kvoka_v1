<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241220001458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entry DROP FOREIGN KEY FK_2B219D7050266CBB');
        $this->addSql('ALTER TABLE entry DROP FOREIGN KEY FK_2B219D707ADA1FB5');
        $this->addSql('DROP INDEX IDX_2B219D707ADA1FB5 ON entry');
        $this->addSql('DROP INDEX IDX_2B219D7050266CBB ON entry');
        $this->addSql('ALTER TABLE entry ADD text VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD color VARCHAR(50) NOT NULL, ADD shape VARCHAR(50) NOT NULL, ADD images JSON NOT NULL, DROP color_id, DROP shape_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entry ADD color_id INT NOT NULL, ADD shape_id INT NOT NULL, DROP text, DROP email, DROP color, DROP shape, DROP images');
        $this->addSql('ALTER TABLE entry ADD CONSTRAINT FK_2B219D7050266CBB FOREIGN KEY (shape_id) REFERENCES shape (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE entry ADD CONSTRAINT FK_2B219D707ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_2B219D707ADA1FB5 ON entry (color_id)');
        $this->addSql('CREATE INDEX IDX_2B219D7050266CBB ON entry (shape_id)');
    }
}
