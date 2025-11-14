<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "usuario")]
class Usuario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 100)]
    private string $nome;

    #[ORM\Column(type: "string", length: 120, unique: true)]
    private string $email;

    #[ORM\Column(type: "string", length: 255)]
    private string $senha;

    #[ORM\Column(type: "string", columnDefinition: "ENUM('admin','responsavel')")]
    private string $tipo;

    #[ORM\OneToOne(mappedBy: "responsavel")]
    private ?Setor $setorResponsavel = null;

    #[ORM\ManyToOne(targetEntity: Setor::class)]
    #[ORM\JoinColumn(name: "setor_id", referencedColumnName: "id", nullable: true)]
    private ?Setor $setor = null;

    #[ORM\OneToMany(mappedBy: "responsavel", targetEntity: Pedido::class)]
    private $pedidos;

    public function __construct()
    {
        $this->pedidos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    // getters e setters...
}
