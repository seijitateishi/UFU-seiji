#include <stdio.h>
#include <stdlib.h>
int main (){
    FILE *p = fopen("teste.txt","r");//cria arquivo texto
    //FILE *p = stdout;//escreve no console(bom p testar)
    if (p == NULL){
        printf("Erro na abertura\n");
        system("pause");
        exit(1);
    }
    //fputs("jaquepa \ntomba",p);
    char str[10];
    int x;
    fscanf(p,"%s",str);
    fscanf(p,"%d",&x);
    printf("%s %d",str,x);
    fclose(p);
    return 0;
}
//char str[tamanho];
//char *fgets(chat *str, int tamanho,FILE *fp);
//str = onde a lista vai ser armazenada
//tamanho = tamanho max da str
//fp = ponteiro 
//tem que usar "r" ao inves de "w"

//fprintf(arq,"escrita");
//fscanf(arq,"%d",&x);

//while(!feof(p)){
//   fscanf(p,"%d",&n);
//   printf("%d\n",n);}

//como usar feof()
//while (1){
//    fscanf(p,"%d",&x);
//    if (feof(p))
//        break;
//    printf("%d",n);}

//int remove(char *nome_do_arq);
