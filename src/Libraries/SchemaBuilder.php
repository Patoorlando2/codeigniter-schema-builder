<?php

namespace App\Libraries;

use CodeIgniter\Database\Forge;

class SchemaBuilder
{
    protected $forge;
    protected $fields = [];
    protected $foreignKeys = [];

    public function __construct()
    {
        $this->forge = \Config\Database::forge();
    }

    // ---------- Tipos de columna ----------
    public function id($name)
    {
        $this->fields[$name] = [
            'type'           => 'INT',
            'constraint'     => 11,
            'unsigned'       => true,
            'auto_increment' => true,
            'null'           => false,
        ];
        
        return $this;
    }

    public function string($name, $length = 255)
    {
        $this->fields[$name] = [
            'type'       => 'VARCHAR',
            'constraint' => $length,
        ];
        return new ColumnModifier($this, $name);
    }

    public function text($name)
    {
        $this->fields[$name] = ['type' => 'TEXT'];
        return new ColumnModifier($this, $name);
    }

    public function integer($name)
    {
        $this->fields[$name] = ['type' => 'INT'];
        return new ColumnModifier($this, $name);
    }

    public function decimal($name, $precision = 10, $scale = 2)
    {
        $this->fields[$name] = [
            'type'       => 'DECIMAL',
            'constraint' => "{$precision},{$scale}",
        ];
        return new ColumnModifier($this, $name);
    }

    public function boolean($name)
    {
        $this->fields[$name] = ['type' => 'TINYINT', 'constraint' => 1];
        return new ColumnModifier($this, $name);
    }

    public function date($name)
    {
        $this->fields[$name] = ['type' => 'DATE'];
        return new ColumnModifier($this, $name);
    }

    public function foreign($column)
    {
        $this->foreignKeys[$column] = [];
        return new ForeignKeyModifier($this, $column);
    }

    public function timestamps()
    {
        $this->fields['created_at'] = 'DATETIME DEFAULT CURRENT_TIMESTAMP';
        $this->fields['updated_at'] = 'DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP';
        return $this;
    }

    // ---------- Ejecución ----------
    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function addForeignKey($column, $foreign)
    {
        $this->foreignKeys[$column] = $foreign;
    }

    public function create($table, $callback)
    {
        $this->table = $table;
        $this->fields = [];

        $callback($this);

        // Agrega todos los campos
        $this->forge->addField($this->fields);

        // 👇 Si no tiene ninguna PK definida, la buscamos automáticamente
        foreach ($this->fields as $name => $field) {
            if (isset($field['auto_increment']) && $field['auto_increment'] === true) {
                $this->forge->addKey($name, true);
            }
        }

        $this->forge->createTable($table, true);
    }
}

// ---------- Modificadores de columna ----------
class ColumnModifier
{
    protected $schema;
    protected $column;

    public function __construct($schema, $column)
    {
        $this->schema = $schema;
        $this->column = $column;
    }

    public function nullable()
    {
        $fields = $this->schema->getFields();
        $fields[$this->column]['null'] = true;
        $this->schema->setFields($fields);
        return $this;
    }

    public function default($value)
    {
        $fields = $this->schema->getFields();
        $fields[$this->column]['default'] = $value;
        $this->schema->setFields($fields);
        return $this;
    }

    public function unsigned()
    {
        $fields = $this->schema->getFields();
        $fields[$this->column]['unsigned'] = true;
        $this->schema->setFields($fields);
        return $this;
    }

    public function unique()
    {
        $fields = $this->schema->getFields();
        $fields[$this->column]['unique'] = true;
        $this->schema->setFields($fields);
        return $this;
    }

    public function end()
    {
        return $this->schema;
    }
}

// ---------- Definición de claves foráneas ----------
class ForeignKeyModifier
{
    protected $schema;
    protected $column;
    protected $references;
    protected $table;
    protected $onDelete = 'CASCADE';
    protected $onUpdate = 'CASCADE';

    public function __construct($schema, $column)
    {
        $this->schema = $schema;
        $this->column = $column;
    }

    public function references($column)
    {
        $this->references = $column;
        return $this;
    }

    public function on($table)
    {
        $this->table = $table;
        $this->schema->addForeignKey($this->column, [
            'references' => $this->references,
            'table'      => $this->table,
            'onDelete'   => $this->onDelete,
            'onUpdate'   => $this->onUpdate,
        ]);
        return $this;
    }

    public function onDelete($action)
    {
        $this->onDelete = strtoupper($action);
        return $this;
    }

    public function onUpdate($action)
    {
        $this->onUpdate = strtoupper($action);
        return $this;
    }
}
