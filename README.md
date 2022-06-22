# Challenge Ingreso Career Switch: DeFI-Solidity
## PHP Version

En este repositorio se encuentra el código para ejecutar el Challenge Ingreso Career Switch: DeFI-Solidity. 

Se compone de:

- Función Check() que cumple con los requerimientos
- API (GET) para probar la función de manera simple con algún cliente Http, como por ejemplo Postman

## Modo de uso de la API

- Hacer una petición de tipo GET a https://challenge-ingreso-lbertran.herokuapp.com/api.php?email=mail-de-prueba

donde mail-de-prueba debe ser reemplazado por el mail que se quiera corroborar.

## Ordenamiento

El algortimo de ordenamiento trabaja de la siguiente manera:

- Se recibe el arreglo de bloques a validar
- Se crea un arreglo nuevo donde estarán los bloques ordenados
- Se van llenando las pocisiones secuencialmente
- Se recorre el arreglo de bloques original comparando cada uno con el último del que se tiene certeza
- Al encontrar el bloque de una pocisión dada, se lo pushea en el nuevo arreglo de ordenados y se lo elimina del arreglo de bvloques original
- Cada vez se vuelve a recrorrer el arreglo de bloques original, con un bloque menos que la vez anterior

## TESTING

Para correr el test de la función check, con API modo Mock, ejecutar en la linea de comandos, desde la raiz del proyecto:

```sh
./vendor/bin/phpunit tests
```

Luego de ejecutarse se visualizará el arreglado ordenado y el resultado del test.