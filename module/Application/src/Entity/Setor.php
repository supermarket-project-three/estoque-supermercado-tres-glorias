<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Application\Entity\Usuario;
use Application\Entity\Produto;

#[ORM\Entity]
#[ORM\Table(name: "setor")]
class Setor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 100)]
    private string $nome;

    #[ORM\OneToOne(targetEntity: Usuario::class, inversedBy: "setorResponsavel")]
    #[ORM\JoinColumn(name: "responsavel_id", referencedColumnName: "id", nullable: true)]
    private ?Usuario $responsavel = null;

    #[ORM\OneToMany(mappedBy: "setor", targetEntity: Produto::class)]
    private Collection $produtos;

    public function __construct()
    {
        $this->produtos = new ArrayCollection();
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

    public function getResponsavel(): ?Usuario
    {
        return $this->responsavel;
    }

    public function setResponsavel(?Usuario $responsavel): self
    {
        $this->responsavel = $responsavel;
        return $this;
    }

    /**
     * @return Collection|Produto[]
     */
    public function getProdutos(): Collection
    {
        return $this->produtos;
    }

  
    public function addProduto(Produto $produto): self
    {
        if (!$this->produtos->contains($produto)) {
            $this->produtos->add($produto);
            $produto->setSetor($this);
        }
        return $this;
    }

    public function removeProduto(Produto $produto): self
    {
        if ($this->produtos->removeElement($produto)) {
            if ($produto->getSetor() === $this) {
                $produto->setSetor(null);
            }
        }
        return $this;
    }
}