#include <stdio.h>
//#include <json-c/json.h> n consegui adicionar o json-c/json.h
int main(){
    //FILE *fp;
    //fp = fopen("dados.json", "r");
    int Vetor[30]; 
    
    int maiorQueaMedia = 0;
    for (int i = 0; i < lenght(Vetor); i++)
    {
        if (Vetor[i] == 0)
        {
            Vetor[i] = NULL;
        }
    }
    
    for (int i = 0; i < lenght(Vetor); i++)
    {
        if (Vetor[i] > mean(Vetor))
        {
            maiorQueaMedia++;
        }
    }
    printf("Maior valor: %d\n", max(Vetor));
    printf("Menor valor: %d\n", min(Vetor));
    printf("Numero de Valores maiores que a media %d\n", maiorQueaMedia);
    return 0;
}