<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "movimento_estoque")]
class MovimentoEstoque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Produto::class)]
    #[ORM\JoinColumn(name: "produto_id", referencedColumnName: "id", nullable: false)]
    private Produto $produto;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: "usuario_id", referencedColumnName: "id", nullable: false)]
    private Usuario $usuario;

    #[ORM\Column(type: "integer")]
    private int $quantidade;

    #[ORM\Column(type: "string", columnDefinition: "ENUM('entrada','saida','ajuste')")]
    private string $tipo;

    #[ORM\Column(type: "datetime")]
    private \DateTime $data;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $observacao = null;

    public function __construct()
    {
        $this->data = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduto(): Produto
    {
        return $this->produto;
    }

    public function setProduto(Produto $produto): self
    {
        $this->produto = $produto;
        return $this;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): self
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getQuantidade(): int
    {
        return $this->quantidade;
    }

    public function setQuantidade(int $quantidade): self
    {
        $this->quantidade = $quantidade;
        return $this;
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;
        return $this;
    }

    public function getData(): \DateTime
    {
        return $this->data;
    }

    public function setData(\DateTime $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    public function setObservacao(?string $observacao): self
    {
        $this->observacao = $observacao;
        return $this;
    }
}
