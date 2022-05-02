<?php

namespace AppFinance\Application\UseCases\MakeTransfer;

use AppFinance\Domain\Entities\Transaction;
use AppFinance\Domain\Enums\UserType;
use AppFinance\Domain\Events\CompletedTransfer;
use AppFinance\Domain\Repositories\ITransactionRepository;
use AppFinance\Domain\Repositories\IUserRepository;
use AppFinance\Domain\Services\Transfer;
use AppFinance\Infra\Event\EventBus;
use AppFinance\Shared\Exceptions\NotFoundException;
use AppFinance\Shared\Exceptions\TransferException;

class MakeTransfer
{
    private ITransactionRepository $transaction_repository;
    private IUserRepository $user_repository;
    private ITransferAuthorizer $transfer_authorizer;
    private EventBus $event_bus;

    public function __construct(
        ITransactionRepository $transaction_repository,
        IUserRepository $user_repository,
        ITransferAuthorizer $transfer_authorizer,
        EventBus $event_bus
    )
    {
        $this->transaction_repository = $transaction_repository;
        $this->user_repository = $user_repository;
        $this->transfer_authorizer = $transfer_authorizer;
        $this->event_bus = $event_bus;
    }

    public function execute(MakeTransferInputDto $inputParams): Transaction
    {
        $user_origin = $this->user_repository->getById($inputParams->getUserOriginId());
        if (empty($user_origin)) {
            throw new NotFoundException("Usuário de origem não foi encontrado.");
        }
        if ($user_origin->getUserType() === UserType::STORE) {
            throw new \DomainException("Usuário lojista não pode efetuar trasnferência");
        }
        $user_destiny = $this->user_repository->getById($inputParams->getUserDestinyId());
        if (empty($user_destiny)) {
            throw new NotFoundException("Usuário de destino não foi encontrado.");
        }
        if ($user_origin->getId() === $user_destiny->getId()) {
            throw new \DomainException("Não é possível realizar transferência para o mesmo usuário da conta de origem.");
        }
        $transfer = Transfer::create(
            $user_origin,
            $user_destiny,
            $inputParams->getValue()
        );
        if (! $this->transfer_authorizer->verify()) {
            throw new TransferException("Transferência não foi autorizada");
        }
        $this->transaction_repository->save($transfer->getTransaction());
        $this->user_repository->save($transfer->getOriginAccount());
        $this->user_repository->save($transfer->getDestinyAccount());
        $this->event_bus->publish(new CompletedTransfer($transfer));

        return $transfer->getTransaction();
    }
}