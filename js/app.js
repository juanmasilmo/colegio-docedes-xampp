//funciones

/* //Funcion clasica

function suma(x){
    return x + 1;
}

//Funcion guardada en una variable

let suma = function(x){
    return x + 1;
}

//Funcion flecha

let suma = (x) => {
    return x + 1;
} */

    let colores = ['rojo', 'amarillo'];

    //para agregar un elemento al array al final se usa el metodo "push"

    colores.push('azul');
    console.log(colores); 

    // para agregua un elemento al principio de array se usa el metodo unshift

    colores.unshift('rosa');
    console.log(colores);

    //para eliminar el ultimo elemento del array se usa el metodo pop()

    colores.pop()
    console.log(colores);

    //para eliminar el primer elemento del array se usa el metodo shift()
    colores.shift();
    console.log(colores);