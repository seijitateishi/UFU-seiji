%{
#include "Lexer.h"
#define YYSTYPE double
#include <string.h>  // Inclua para manipular strings
char postfix[256] = ""; // Para armazenar a notação pós-fixa

void yyerror(char *);
%}

%token NUM
%left '+' '-'
%left '*' '/'
%right NEGAR

%%
lines : lines expr '\n'  { printf("Resultado: %.2lf\n", $2); printf("Notação Pós-Fixa: %s\n", postfix); postfix[0] = '\0'; }
      | lines '\n'       { /* nada */ }
      | error '\n'       { yyerror("Erro na última linha"); yyerrok; }
      ;

expr : expr '+' expr   { $$ = $1 + $3; strcat(postfix, " +"); }
     | expr '-' expr   { $$ = $1 - $3; strcat(postfix, " -"); }
     | expr '*' expr   { $$ = $1 * $3; strcat(postfix, " *"); }
     | expr '/' expr   { $$ = $1 / $3; strcat(postfix, " /"); }
     | '(' expr ')'    { $$ = $2; }
     | '-' expr %prec NEGAR { $$ = -$2; strcat(postfix, " NEG"); }
     | NUM             { $$ = $1; char num_str[32]; sprintf(num_str, " %.2lf", $1); strcat(postfix, num_str); }
     ;
%%

void yyerror(char *s) {
    fprintf(stderr, "%s\n", s);
}

int main(void) {
    return yyparse();
}
