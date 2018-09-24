# Prueba tecnica

**Sistema de menu-multinivel con laravel y vuejs.**

## Usar

Para clonar y ejecutar este repositorio, necesitará [Git](https://git-scm.com) y [Node.js](https://nodejs.org/en/download/) (que viene con [npm](http://npmjs.com)) instalado en su computadora. Desde tu línea de comando:

```bash
# Clone this repository
git clone https://github.com/AlbertoTorre/prueba_websas.git
# Go into the repository
cd prueba_websas

# Database migration:
Primero debemos crear  la base de datos con el nombre  prueba_websas o cualquier otro, 
este nombre lo ponemos en el archivo .env

php  artisan  migrate  

Para ejecutar vuejs en el proyecto el comando  npm update y luego npm run dev.

Nota: Laravel tiene un problema de vulnerabilidades en si paqueye laravel-mix,
Si esto les pasa los paquetes se deben instalar con npm i laravel-mix --only=dev