#include <stdio.h>
#include <stdlib.h>

int isFibonacci(int n){
    int a = 0, b = 1, c = 0;
    while (c < n)
    {
        c = a + b;
        a = b;
        b = c;
    }
    if (c == n){
        return 1;
    }else{
        return 0;
    }    
}

int main(){
    int input = 7;
    if (isFibonacci(input))
        printf("%d eh fibonacci\n", input);
    else
        printf("%d eh nao fibonacci\n", input);

    return 0;
}