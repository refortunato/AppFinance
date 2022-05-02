<?php

namespace AppFinance\Infra\Controllers;

use AppFinance\Application\UseCases\GetUserByToken\GetUserByToken;
use AppFinance\Application\UseCases\ListAllTransactionsOfAccount\ListAllTransactionsOfAccount;
use AppFinance\Application\UseCases\MakeTransfer\MakeTransfer;
use AppFinance\Application\UseCases\MakeTransfer\MakeTransferInputDto;
use AppFinance\Domain\Events\Handler\CompletedTransferSendMailHandler;
use AppFinance\Domain\Mappers\TransactionMap;
use AppFinance\Infra\DB\DataMapper\Drivers\DriverFactory;
use AppFinance\Infra\Encrypter\JwtAdapter;
use AppFinance\Infra\Event\EventBus;
use AppFinance\Infra\Mail\EmailSenderMock;
use AppFinance\Infra\Repositories\Sql\RepositorySqlFactory;
use AppFinance\Infra\TransferAuthorizer\TransferAuthorizerMock;
use AppFinance\Shared\Controller\ControllerBase;
use AppFinance\Shared\Controller\RequestController;
use AppFinance\Shared\Controller\TokenHelper;
use AppFinance\Shared\Exceptions\TransferException;
use AppFinance\Shared\Validators\FieldsArrayValidator;

class TransactionController extends ControllerBase
{
    use TokenHelper;

    protected static function makeTransfer(RequestController $request): ?array
    {
        $body = $request->getBody();
        FieldsArrayValidator::create($body)
          ->checkField('destiny_account_id')
          ->checkField('value', 'Valor', 'numeric');          
        $driver = DriverFactory::makeSqlDriver();
        $user_repository = RepositorySqlFactory::getUserRepository($driver);
        $transaction_repository = RepositorySqlFactory::getTransactionRepository($driver);
        $transfer_authorizator = new TransferAuthorizerMock();
        $email_sender = new EmailSenderMock();
        $eventBus = new EventBus();
        $eventBus->subscribe("CompletedTransfer", new CompletedTransferSendMailHandler($email_sender));
        $origin_account = self::getUserFromToken($request);

        $inputDto = new MakeTransferInputDto(
             $origin_account->getId(),
             $body['destiny_account_id'],
             (float) $body['value']
        );
        $use_case = new MakeTransfer(
            $transaction_repository,
            $user_repository,
            $transfer_authorizator,
            $eventBus
        );
        try {
            $driver->beginTransaction();
            $transaction = $use_case->execute($inputDto);
            $driver->commit();
            return TransactionMap::toArray($transaction);
        } catch (TransferException $e) {
            $driver->rollBack();
            throw new \DomainException($e->getMessage());
        } finally {
            if ($driver->inTransaction()) {
                $driver->rollBack();
            }
        }
    }

    protected static function getAllOfUser(RequestController $request): ?array
    {
        $transaction_repository = RepositorySqlFactory::getTransactionRepository();
        $use_case = new ListAllTransactionsOfAccount($transaction_repository);
        $user = self::getUserFromToken($request);
        $transactions = $use_case->execute($user->getId());
        $transaction_array = [];
        foreach ($transactions as $transaction) {
            $transaction_array[] = TransactionMap::toArray($transaction);
        }
        return $transaction_array ;
    }
}