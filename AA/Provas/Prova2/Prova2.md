1-
for(i = 0; i < n; i++)
    if (i == V[i])
        return true
return false

2-
x1 e x4 < 0
|x1|>x2
|x4|>x3
x3 e x2 podem ser positivos ou negativos
S1 | X1 | X2 | X3 | X4 | S2
10   -3   2    3    -5   10 = S1~S2
10   -3   2    3    -5   6  = S1~X3
10   -9   2    2    -9   4  = S1
3    -3   2    2    -3   3  = X2~X3
com valores diferente tambem é possivel conseguir resultados espelhados,porem nao convem mostra-los

3-


4- 
O calculo da raiz não toma tempo constante pois a raiz pode ter números com muitas casas decimais, portanto ao mudar o metodo de contar a distancia para um pow e soma, que tem o tempo constante é possivel fazer a analise assintotica com mais precisao 