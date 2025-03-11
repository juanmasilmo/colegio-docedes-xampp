# README - Sistema de Gestión Escolar

## Descripción
Este es un sistema de gestión escolar desarrollado en PHP que permite la administración de usuarios, roles y personas, con un sistema de autenticación seguro.

## Estructura del Proyecto

### Directorio `/includes`

#### `config.php`
- Contiene las constantes de configuración de la base de datos
- Define los parámetros de conexión (host, usuario, contraseña, nombre de la base de datos)

#### `database.php`
- Implementa el patrón Singleton para la conexión a la base de datos
- Proporciona métodos para:
  - Gestión de conexiones a la base de datos
  - Manejo de sesiones de usuario
  - Verificación de sesiones activas
  - Obtención de roles de usuario
  - Cierre de sesiones

#### `session.php`
- Inicializa la sesión PHP para todo el sistema
- Punto central para el manejo de sesiones

#### `auth.php`
- Gestiona la autenticación de usuarios
- Proporciona métodos para:
  - Login de usuarios
  - Verificación de roles
  - Logout
  - Implementa medidas de seguridad (regeneración de ID de sesión, tokens CSRF)

#### `functions.php`
- Contiene funciones auxiliares:
  - `sanitizeInput()`: Limpieza de datos de entrada
  - `generateCSRFToken()`: Generación de tokens CSRF
  - `validateCSRFToken()`: Validación de tokens CSRF
  - `redirect()`: Función de redirección

### Directorio `/pages`

#### `login.php`
- Página de inicio de sesión
- Valida credenciales de usuario
- Redirecciona según el rol del usuario (admin/user)
- Muestra mensajes de error/éxito
- Proporciona enlace para crear nuevos usuarios

#### `crear-usuario.php`
- Formulario de registro de nuevos usuarios
- Recopila información personal y de usuario
- Realiza validaciones:
  - Verifica duplicados de DNI/CUIL
  - Verifica nombres de usuario existentes
- Almacena datos en las tablas:
  - `personas`: información personal
  - `usuarios`: credenciales y roles
- Implementa hash seguro de contraseñas

#### `logout.php`
- Maneja el cierre de sesión
- Destruye la sesión actual
- Redirecciona al login

## Características de Seguridad
- Protección contra inyección SQL usando PreparedStatements
- Hash seguro de contraseñas
- Manejo de sesiones seguro
- Validación y sanitización de entrada de datos
- Protección CSRF
- Control de acceso basado en roles

## Base de Datos
El sistema utiliza una base de datos MySQL con las siguientes tablas principales:
- `usuarios`: Almacena credenciales y datos de acceso
- `personas`: Almacena información personal
- `roles`: Define los roles del sistema

## Requisitos
- PHP 8.2.12
- MySQL/MariaDB
- Apache 2.4.58
- OpenSSL 3.1.3

## Instalación
1. Clonar el repositorio en el directorio web
2. Configurar los parámetros de base de datos en `includes/config.php`
3. Importar la estructura de base de datos (script no incluido)
4. Asegurarse que el servidor web tenga permisos de escritura en los directorios necesarios

## Uso
1. Acceder a la página de login
2. Iniciar sesión con credenciales válidas
3. El sistema redirigirá según el rol del usuario
4. Los nuevos usuarios pueden registrarse desde el enlace en la página de login

## Seguridad
Se recomienda:
- Modificar las credenciales por defecto de la base de datos
- Configurar HTTPS
- Mantener actualizado el sistema y sus dependencias
- Realizar copias de seguridad regulares
