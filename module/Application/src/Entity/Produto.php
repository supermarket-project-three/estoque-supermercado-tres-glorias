<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "produto")]
class Produto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 150)]
    private string $nome;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $descricao;

    #[ORM\Column(type: "string", length: 70, nullable: true)]
    private ?string $codigoBarras;

    #[ORM\ManyToOne(targetEntity: Setor::class, inversedBy: "produtos")]
    #[ORM\JoinColumn(name: "setor_id", referencedColumnName: "id")]
    private Setor $setor;

    #[ORM\Column(type: "integer")]
    private int $estoqueAtual;

    #[ORM\Column(type: "integer")]
    private int $estoqueMinimo;

    #[ORM\Column(type: "boolean")]
    private bool $ativo = true;

    #[ORM\OneToMany(mappedBy: "produto", targetEntity: Pedido::class)]
    private $pedidos;

    public function __construct()
    {
        $this->pedidos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    // getters e setters...
}
