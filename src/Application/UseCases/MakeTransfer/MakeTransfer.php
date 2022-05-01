<?php

namespace AppFinance\Application\UseCases\MakeTransfer;

use AppFinance\Domain\Entities\Transfer;
use AppFinance\Domain\Enums\UserType;
use AppFinance\Domain\Events\CompletedTransfer;
use AppFinance\Domain\Repositories\ITransferRepository;
use AppFinance\Domain\Repositories\IUserRepository;
use AppFinance\Infra\Event\EventBus;
use AppFinance\Shared\Exceptions\NotFoundException;

class MakeTransfer
{
    private ITransferRepository $transfer_repository;
    private IUserRepository $user_repository;
    private ITransferAuthorizer $transfer_authorizer;
    private EventBus $event_bus;

    public function __construct(
        ITransferRepository $transfer_repository,
        IUserRepository $user_repository,
        ITransferAuthorizer $transfer_authorizer,
        EventBus $event_bus
    )
    {
        $this->transfer_repository = $transfer_repository;
        $this->user_repository = $user_repository;
        $this->transfer_authorizer = $transfer_authorizer;
        $this->event_bus = $event_bus;
    }

    public function execute(MakeTransferInputDto $inputParams)
    {
        $user_origin = $this->user_repository->getById($inputParams->getUserOriginId());
        if (empty($user_origin)) {
            throw new NotFoundException("Usuário de origem não foi encontrado.");
        }
        if (! $user_origin->getUserType() === UserType::STORE) {
            throw new \DomainException("Usuário lojista não pode efetuar trasnferência");
        }
        $user_destiny = $this->user_repository->getById($inputParams->getUserDestinyId());
        if (empty($user_destiny)) {
            throw new NotFoundException("Usuário de destino não foi encontrado.");
        }
        $transfer = new Transfer(
            '',
            $user_origin,
            $user_destiny,
            $inputParams->getValue()
        );
        if (! $this->transfer_authorizer->verify()) {
            throw new \DomainException("Transferência não foi autorizada");
        }
        $this->transfer_repository->save($transfer);
        $this->event_bus->publish(new CompletedTransfer($transfer));
    }
}