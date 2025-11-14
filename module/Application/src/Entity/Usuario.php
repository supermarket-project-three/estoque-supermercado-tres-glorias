<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
// Importações necessárias para os relacionamentos
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\Setor;
use Application\Entity\Pedido;

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
    private string $senha; // Guarda o HASH

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
        $this->pedidos = new ArrayCollection();
    }

    // --- Métodos de Senha (Essenciais) ---

    /**
     * Gera o hash da senha antes de salvar
     */
    public function setSenhaComHash(string $senhaPura): self
    {
        $this->senha = password_hash($senhaPura, PASSWORD_DEFAULT);
        return $this;
    }

    /**
     * Verifica se a senha pura bate com o hash salvo
     */
    public function verificarSenha(string $senhaPura): bool
    {
        return password_verify($senhaPura, $this->senha);
    }

    // --- Getters e Setters Padrão ---

    public function getId(): int { return $this->id; }

    public function getNome(): string { return $this->nome; }
    public function setNome(string $nome): self { $this->nome = $nome; return $this; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }

    public function getSenha(): string { return $this->senha; }
    public function setSenha(string $senha): self { $this->senha = $senha; return $this; }

    public function getTipo(): string { return $this->tipo; }
    public function setTipo(string $tipo): self { $this->tipo = $tipo; return $this; }

    public function getSetor(): ?Setor { return $this->setor; }
    public function setSetor(?Setor $setor): self { $this->setor = $setor; return $this; }
}