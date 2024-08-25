/*#include "lexer.h"
#include "exp.h"
#include <stdio.h>

// Carrega uma string como entrada
YY_BUFFER_STATE buffer;

void inicializa(char* str) {
    buffer = yy_scan_string(str);
}

Token* proximo_token() {
    return yylex();
}

void imprime_token(Token* tok) {
    if (tok->lexema) {
        printf("<%d, %s>\n", tok->tipo, tok->lexema);
    } else {
        printf("<%d, ->\n", tok->tipo);
    }
}

int main(int argc, char** argv) {
    Token* tok;
    char entrada[200];

    printf("\nAnalise Lexica da expressao: ");
    fgets(entrada, 200, stdin);
    inicializa(entrada);

    tok = proximo_token();
    while (tok != NULL) {
        imprime_token(tok);
        tok = proximo_token();
    }

    return 0;
}
*/
#include "exp.h"
#include "lexer.h"
#include <string.h>
#include <stdio.h>

YY_BUFFER_STATE buffer;

void start(char *str) { buffer = yy_scan_string(str); }

Token *get_next_token() { return yylex(); }
/// @brief Printa no stdout o token passado
/// @param tok
void print_token(Token *tok)
{
  // Print token in <tok.type, tok->value>, format.
  // Check if value is null and print - in its place if so.
  if (tok->value == NULL)
  {
    printf("<%s, - >", tok->type);
  }
  else
  {
    printf("<%s, %s>", tok->type, tok->value);
  }
}
char *get_file_path(int argc, char **argv)
{
  char *file_path;
  if (argc > 1)
  {
    return argv[1];
  }
  file_path = (char *)malloc(200 * sizeof(char));
  printf("\nFile Path: ");
  fgets(file_path, 200, stdin);
  // Remove newline character from file_path
  file_path[strcspn(file_path, "\n")] = 0;
  return file_path;
}

char *get_file_contents(char *file_path)
{
  FILE *file;
  file = fopen(file_path, "r");
  if (file == NULL)
  {
    printf("Error! Could not open file\n");
    return NULL;
  }
  fseek(file, 0, SEEK_END);
  long file_size = ftell(file);
  rewind(file);

  char *file_contents = (char *)malloc(file_size * sizeof(char));

  fread(file_contents, sizeof(char), file_size, file);
  return file_contents;
}

int main(int argc, char **argv)
{
  Token *tok;

  char *file_path = get_file_path(argc, argv);
  char *file_contents = get_file_contents(file_path);

  if (file_contents == NULL)
  {
    return 1;
  }
  start(file_contents);

  tok = get_next_token();
  while (tok != NULL)
  {
    print_token(tok);

    if (strcmp(tok->type, TOK_EOF) == 0)
    {
      break;
    }

    if (strcmp(tok->type, TOK_ERROR) == 0)
    {
      printf("Error! Unknown token found: %s\n", tok->value);
      break;
    }

    tok = get_next_token();
  }

  return 0;
}