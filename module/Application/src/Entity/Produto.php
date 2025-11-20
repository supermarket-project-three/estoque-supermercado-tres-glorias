<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Application\Entity\Setor;
use Application\Repository\ProdutoRepository;

#[ORM\Entity(repositoryClass: ProdutoRepository::class)]
#[ORM\Table(name: "produto")]
class Produto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 150)]
    private string $nome;

    #[ORM\Column(type: "string", length: 100)]
    private string $marca;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $descricao;

    #[ORM\ManyToOne(targetEntity: Setor::class, inversedBy: "produtos")]
    #[ORM\JoinColumn(name: "setor_id", referencedColumnName: "id")]
    private Setor $setor;

    #[ORM\Column(type: "integer")]
    private int $quantidade = 0;

    #[ORM\Column(type: "boolean")]
    private bool $ativo = true;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private float $preco = 0;

    public function __construct()
    {
        
    }

    // --- GETTERS E SETTERS ---

    public function getId(): int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;
        return $this;
    }

    public function getMarca(): string
    {
        return $this->marca;
    }

    public function setMarca(string $marca): self
    {
        $this->marca = $marca;
        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): self
    {
        $this->descricao = $descricao;
        return $this;
    }

    public function getSetor(): Setor
    {
        return $this->setor;
    }

    public function setSetor(Setor $setor): self
    {
        $this->setor = $setor;
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

    public function isAtivo(): bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): self
    {
        $this->ativo = $ativo;
        return $this;
    }

    public function getPreco(): float
    {
        return $this->preco;
    }

    public function setPreco(float $preco): self
    {
        $this->preco = $preco;
        return $this;
    }

    
}