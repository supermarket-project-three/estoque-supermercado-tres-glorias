<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Application\Entity\Setor;
use Application\Entity\Pedido;
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

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $descricao; // O '?' indica que pode ser nulo

    #[ORM\Column(type: "string", length: 70, nullable: true)]
    private ?string $codigoBarras; // O '?' indica que pode ser nulo

    #[ORM\ManyToOne(targetEntity: Setor::class, inversedBy: "produtos")]
    #[ORM\JoinColumn(name: "setor_id", referencedColumnName: "id")]
    private Setor $setor;

    #[ORM\Column(type: "integer")]
    private int $estoqueAtual;

    #[ORM\Column(type: "integer")]
    private int $estoqueMinimo;

    #[ORM\Column(type: "boolean")]
    private bool $ativo = true;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private float $preco = 0; // Preço do produto

    #[ORM\OneToMany(mappedBy: "produto", targetEntity: Pedido::class)]
    private Collection $pedidos; // Tipado como Collection

    public function __construct()
    {
        $this->pedidos = new ArrayCollection();
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

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): self
    {
        $this->descricao = $descricao;
        return $this;
    }

    public function getCodigoBarras(): ?string
    {
        return $this->codigoBarras;
    }

    public function setCodigoBarras(?string $codigoBarras): self
    {
        $this->codigoBarras = $codigoBarras;
        return $this;
    }

    public function getSetor(): Setor
    {
        return $this->setor;
    }

    public function setSetor(?Setor $setor): self
    {
        $this->setor = $setor;
        return $this;
    }

    public function getEstoqueAtual(): int
    {
        return $this->estoqueAtual;
    }

    public function setEstoqueAtual(int $estoqueAtual): self
    {
        $this->estoqueAtual = $estoqueAtual;
        return $this;
    }

    public function getEstoqueMinimo(): int
    {
        return $this->estoqueMinimo;
    }

    public function setEstoqueMinimo(int $estoqueMinimo): self
    {
        $this->estoqueMinimo = $estoqueMinimo;
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

    /**
     * @return Collection|Pedido[]
     */
    public function getPedidos(): Collection
    {
        return $this->pedidos;
    }

}