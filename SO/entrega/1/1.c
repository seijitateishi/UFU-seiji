#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <dirent.h>
#include <errno.h>
#include <sys/stat.h>
#include <time.h>
#include <unistd.h>
int main()
{
  printf("digite 'SAIR' para sair do programa");
  char entrada[10], nome[50];
  while (strcmp(entrada, "SAIR") != 0)
  {
    setbuf(stdin, NULL);
    gets(entrada);
    setbuf(stdin, NULL);

    if (strcmp(entrada, "CLS") == 0)
      printf("\e[1;1H\e[2J");//ao printar isso,é como a função cls do cmd q limpa o historico das mensagens
    else if (strcmp(entrada, "DIR") == 0)
    {
      printf("nome: ");
      gets(nome);
      char path[50] = "/home/";
      strcat(path, nome);

      struct dirent *dir;

      DIR *d;
      d = opendir(path);
      char full_path[1000];
      if (d)//enquanto houver adiciona os nomes das pastas/arquivos como passa por todas
      {
        while ((dir = readdir(d)) != NULL)
        {
          if (dir->d_type == DT_REG)
          {
            full_path[0] = '\0';
            strcat(full_path, path);
            strcat(full_path, "/");
            strcat(full_path, dir->d_name);
            printf("%s\n", full_path);
          }
        }
        closedir(d);
      }
    }
    else if (strcmp(entrada, "DATE") == 0)
    {//com a biblioteca time é possivel receber o tempo que esta na maquina
      time_t data = time(NULL);
      struct tm formato;
      char aux[20];
      formato = *localtime(&data);
      strftime(aux, sizeof(aux), "%d:%m:%Y", &formato);
      printf("%s\n", aux);
    }
    else if (strcmp(entrada, "TIME") == 0)
    {//tambem usada na TIME,só que com mais precisao
      time_t hora = time(NULL);
      struct tm formato;
      char aux[20];
      formato = *localtime(&hora);
      strftime (aux,sizeof(aux), "&H: %M: %S", &formato);
      printf("%s\n",aux);
    }
  }

  return 0;
}