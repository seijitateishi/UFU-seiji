1-
n^n > n! > 2^n > n^4 > n * log n > n 

2- 
complexidade constante pois com um número fixo não é
uma análise assintótica

3-
//merge de um item no vetor entao acaba sendo insert sort
V = [1,5,8,2,6,7,9]//n == 7
ord = []
i = 1
j = 1
while (j <= n) 
    while (j < n && V[j] <= V[j+1])
        j++
    ord = merge(ord,V[i:j]) //
    j++
    i = j
return ord
j começa == 1
entra no while
entra no 2while
j++ j == 2
j++ j == 3
sai do 2 while
ord = merge(ord,V[1:3]) //merge de [1,5,8]
j++ j == 4
i == 4
entra no 2while
j++ j == 5
j++ j == 6
j++ j == 7
sai do 2 while
ord = merge (ord,V[4:7]) //merge de ord([1,5,8]) e [2,6,7,9]
j++ j == 8
i == 8
sai do 1 while
retorna ord
ele faz merge de um vetor ordenados ou de um elemento com todos os merges ja feitos anteriormente ou um vetor vazia(primeiro merge)

b)
sua complexidade é O(n)

4-
bubblesort tem complexidade omega(n)
quicksort tem complexidade omega(n log n)

5-
for (i = 0; i < n ; i++){//for de 0 a n
    soma = 0

    for (j = i; j <= n; j++){//for de i ate n
        soma =+ V[j]
        final = max(final,soma)//atualiza o valor maximo ou mantem
    }
    
    soma = 0

    for(k = i; k >= 0; k--){//for de i ate 0
        soma += V[k]
        final = max(final,soma)//atualiza o valor maximo ou mantem
    }
}
return final
O(n^2)
o(n^2)

6-
for (i = 0; i < n; i++)
    for(j = 0)    