<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "pedido")]
class Pedido
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    // Usuário que fez o pedido
    #[ORM\ManyToOne(targetEntity: Usuario::class, inversedBy: "pedidos")]
    #[ORM\JoinColumn(name: "usuario_id", referencedColumnName: "id", nullable: false)]
    private Usuario $usuario;

    // Setor solicitante
    #[ORM\ManyToOne(targetEntity: Setor::class)]
    #[ORM\JoinColumn(name: "setor_id", referencedColumnName: "id", nullable: false)]
    private Setor $setor;

    #[ORM\Column(type: "datetime")]
    private \DateTime $data;

    #[ORM\Column(type: "string", columnDefinition: "ENUM('pendente', 'aprovado', 'rejeitado', 'finalizado')")]
    private string $status = "pendente";

    // Relacionamento com itens do pedido
    #[ORM\OneToMany(
        mappedBy: "pedido",
        targetEntity: PedidoItem::class,
        cascade: ["persist", "remove"],
        orphanRemoval: true
    )]
    private Collection $itens;

    public function __construct()
    {
        $this->data = new \DateTime();
        $this->itens = new ArrayCollection();
    }

    // --------------------------
    // GETTERS E SETTERS
    // --------------------------

    public function getId(): int
    {
        return $this->id;
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

    public function getSetor(): Setor
    {
        return $this->setor;
    }

    public function setSetor(Setor $setor): self
    {
        $this->setor = $setor;
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return Collection|PedidoItem[]
     */
    public function getItens(): Collection
    {
        return $this->itens;
    }

    public function addItem(PedidoItem $item): self
    {
        if (!$this->itens->contains($item)) {
            $this->itens->add($item);
            $item->setPedido($this);
        }
        return $this;
    }

    public function removeItem(PedidoItem $item): self
    {
        if ($this->itens->removeElement($item)) {
            if ($item->getPedido() === $this) {
                $item->setPedido(null);
            }
        }
        return $this;
    }
}
