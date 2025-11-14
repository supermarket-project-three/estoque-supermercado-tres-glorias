<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    private $produtos;

    public function __construct()
    {
        $this->produtos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    // getters e setters...
}
