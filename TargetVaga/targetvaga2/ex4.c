int main(){
    float sp = 67836.43, rj = 36678.66, mg = 29229.88, es = 27165.48, outros = 19849.53, soma;
    soma = sp + rj + mg + es + outros;
    sp = sp / soma * 100.0;
    rj = rj / soma * 100.0;
    mg = mg / soma * 100.0;
    es = es / soma * 100.0;
    printf("sp = %f\n",sp);
    printf("rj = %f\n",rj);
    printf("mg = %f\n",mg);
    printf("es = %f\n",es);
    return 0;
}