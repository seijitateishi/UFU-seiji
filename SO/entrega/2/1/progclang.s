	.text
	.file	"ex1.c"
	.globl	main                            # -- Begin function main
	.p2align	4, 0x90
	.type	main,@function
main:                                   # @main
	.cfi_startproc
# %bb.0:
	pushq	%rbp
	.cfi_def_cfa_offset 16
	.cfi_offset %rbp, -16
	movq	%rsp, %rbp
	.cfi_def_cfa_register %rbp
	subq	$16, %rsp
	movl	$3, -8(%rbp)
	movabsq	$.L.str, %rdi
	movb	$0, %al
	callq	printf
	movl	i, %esi
	movl	j, %edx
	movl	-4(%rbp), %ecx
	movl	-8(%rbp), %r8d
	movabsq	$.L.str.1, %rdi
	movb	$0, %al
	callq	printf
	xorl	%eax, %eax
	addq	$16, %rsp
	popq	%rbp
	.cfi_def_cfa %rsp, 8
	retq
.Lfunc_end0:
	.size	main, .Lfunc_end0-main
	.cfi_endproc
                                        # -- End function
	.type	i,@object                       # @i
	.data
	.globl	i
	.p2align	2
i:
	.long	3                               # 0x3
	.size	i, 4

	.type	.L.str,@object                  # @.str
	.section	.rodata.str1.1,"aMS",@progbits,1
.L.str:
	.asciz	"Hello World !\n"
	.size	.L.str, 15

	.type	.L.str.1,@object                # @.str.1
.L.str.1:
	.asciz	"% d % d % d % d"
	.size	.L.str.1, 16

	.type	j,@object                       # @j
	.bss
	.globl	j
	.p2align	2
j:
	.long	0                               # 0x0
	.size	j, 4

	.ident	"Ubuntu clang version 14.0.0-++20210816052626+d6fe8d37c68d-1~exp1~20210816153436.513"
	.section	".note.GNU-stack","",@progbits
	.addrsig
	.addrsig_sym printf
	.addrsig_sym i
	.addrsig_sym j