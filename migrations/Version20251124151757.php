<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251124151757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des relations statut_id et responsable_id sur ticket (migration corrigée)';
    }

    public function up(Schema $schema): void
    {
        $ticket = $schema->getTable('ticket');

        // Ajouter statut_id si absent
        if (!$ticket->hasColumn('statut_id')) {
            $this->addSql('ALTER TABLE ticket ADD statut_id INT NOT NULL');
        }

        // Ajouter responsable_id si absent
        if (!$ticket->hasColumn('responsable_id')) {
            $this->addSql('ALTER TABLE ticket ADD responsable_id INT DEFAULT NULL');
        }

        // Ne PAS supprimer les anciennes colonnes si elles n'existent plus
        if ($ticket->hasColumn('statut')) {
            $this->addSql('ALTER TABLE ticket DROP statut');
        }

        if ($ticket->hasColumn('responsable')) {
            $this->addSql('ALTER TABLE ticket DROP responsable');
        }

        // Ajouter contraintes si pas déjà présentes
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA353C59D72 FOREIGN KEY (responsable_id) REFERENCES responsable (id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3F6203804 ON ticket (statut_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA353C59D72 ON ticket (responsable_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3F6203804');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA353C59D72');
        $this->addSql('DROP INDEX IDX_97A0ADA3F6203804 ON ticket');
        $this->addSql('DROP INDEX IDX_97A0ADA353C59D72 ON ticket');

        // Restaurer anciennes colonnes uniquement si non présentes
        $ticket = $schema->getTable('ticket');

        if (!$ticket->hasColumn('statut')) {
            $this->addSql('ALTER TABLE ticket ADD statut VARCHAR(50) NOT NULL');
        }

        if (!$ticket->hasColumn('responsable')) {
            $this->addSql('ALTER TABLE ticket ADD responsable VARCHAR(150) DEFAULT NULL');
        }

        if ($ticket->hasColumn('statut_id')) {
            $this->addSql('ALTER TABLE ticket DROP statut_id');
        }

        if ($ticket->hasColumn('responsable_id')) {
            $this->addSql('ALTER TABLE ticket DROP responsable_id');
        }
    }
}
