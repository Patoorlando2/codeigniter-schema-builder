**CodeIgniter SchemaBuilder** 
es una pequeña librería para CodeIgniter 4 que facilita la creación de tablas en migraciones mediante una sintaxis simple y fluida parecida a Laravel.

---

## 🚀 Instalación

Podés instalarlo directamente desde Composer:

```bash
composer require patoorlando2/codeigniter-schemaBuilder

O bien, clonar el repositorio manualmente:

git clone https://github.com/patoorlando2/codeigniter-schemaBuilder.git

⚙️ Configuración

Si instalás el paquete manualmente, asegurate de registrar el helper en tu autoload.php o cargarlo directamente:
  reemplazá public $helpers = []; por public $helpers = ['schema'];


Uso de Migraciones:

use CodeIgniter\Database\Migration;

class CreateCategoriasTable extends Migration
{
    public function up()
    {
        forge_schema('categorias', function($table) {
            $table->id('id_categoria');
            $table->string('nombre', 45)->unique();
        });
    }

    public function down()
    {
        $this->forge->dropTable('categorias');
    }
}

Métodos disponibles
$table->id('nombre')	Crea una columna ID autoincremental con clave primaria
$table->string('campo', longitud)	Crea un campo tipo VARCHAR
$table->integer('campo')	Crea un campo entero
$table->foreign('columna')->references('id')->on('tabla')	Agrega clave foránea

Y mucho más... Entrando a Libraries/SchemaBuilder.php tenés todos los métodos agregados

💬 Si te resultó útil, considerá dejar una ⭐ en el repositorio.
Ayudás a que más desarrolladores descubran SchemaBuilder ❤️
