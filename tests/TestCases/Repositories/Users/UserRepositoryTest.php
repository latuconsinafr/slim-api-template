<?php

declare(strict_types=1);

namespace App\Tests\TestCases\Repositories\Users;

use App\Data\Entities\UserEntity;
use App\Data\TypeCasts\Uuid;
use App\Repositories\Users\UserRepositoryInterface;
use App\Tests\Fixtures\UserFixture;
use App\Tests\Traits\AppTestTrait;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * User Repository Test.
 */
final class UserRepositoryTest extends TestCase
{
    use AppTestTrait;

    /**
     * The findAll test.
     * 
     * @return void
     */
    public function testFindAll(): void
    {
        $repository = $this->container->get(UserRepositoryInterface::class);
        $userFixture = new UserFixture();

        $usersSample = $userFixture->getUsers();
        $count = count((array)$usersSample);

        $users = $repository->findAll();

        $this->assertCount($count, $users);

        foreach ($users as $key => $value) {
            $this->assertInstanceOf(UserEntity::class, $users[$key]);
            $this->assertEquals($usersSample[$key]->id, $users[$key]->id);
            $this->assertEquals($usersSample[$key]->userName, $users[$key]->userName);
            $this->assertEquals($usersSample[$key]->email, $users[$key]->email);
            $this->assertEquals($usersSample[$key]->phoneNumber, $users[$key]->phoneNumber);
            $this->assertEquals($usersSample[$key]->password, $users[$key]->password);
        }
    }

    /**
     * The findOne positive scenario test: found.
     * 
     * @return void
     */
    public function testFindOne_found(): void
    {
        $repository = $this->container->get(UserRepositoryInterface::class);
        $userFixture = new UserFixture();

        $user = $repository->findOne(['userName' => $userFixture->user1UserName]);

        $this->assertNotEmpty($user);
        $this->assertNotNull($user);
        $this->assertInstanceOf(UserEntity::class, $user);
    }

    /**
     * The findOne negative scenario test: not found.
     * 
     * @return void
     */
    public function testFindOne_notFound(): void
    {
        $repository = $this->container->get(UserRepositoryInterface::class);
        $userFixture = new UserFixture();

        $user = $repository->findOne(['email' => $userFixture->user1PhoneNumber]);

        $this->assertEmpty($user);
        $this->assertNull($user);
        $this->assertNotInstanceOf(UserEntity::class, $user);
    }

    /**
     * The findById positive scenario test: found.
     * 
     * @return void
     */
    public function testFindById_found(): void
    {
        $repository = $this->container->get(UserRepositoryInterface::class);
        $userFixture = new UserFixture();

        $user = $repository->findById(Uuid::fromString($userFixture->user1Id));

        $this->assertNotEmpty($user);
        $this->assertNotNull($user);
        $this->assertInstanceOf(UserEntity::class, $user);
    }

    /**
     * The findById negative scenario test: not found.
     * 
     * @return void
     */
    public function testFindById_notFound(): void
    {
        $repository = $this->container->get(UserRepositoryInterface::class);

        $user = $repository->findById(Uuid::fromString('00000000-0000-0000-0000-000000000000'));

        $this->assertEmpty($user);
        $this->assertNull($user);
        $this->assertNotInstanceOf(UserEntity::class, $user);
    }

    /**
     * The create positive scenario test: created.
     * 
     * @return void
     */
    public function testCreate_created(): void
    {
        $this->expectNotToPerformAssertions();

        $repository = $this->container->get(UserRepositoryInterface::class);
        $user = new UserEntity();

        $user->id = Uuid::create();
        $user->userName = 'tes';
        $user->email = 'tes@gmail.com';
        $user->phoneNumber = '+6282246920112';
        $user->password = 'user123';

        $repository->create($user);
    }

    /**
     * The create negative scenario test: id does exist.
     * 
     * @return void
     */
    public function testCreate_idDoesExist(): void
    {
        $this->expectException(\Spiral\Database\Exception\StatementException\ConstrainException::class);

        $repository = $this->container->get(UserRepositoryInterface::class);
        $userFixture = new UserFixture();

        $user = new UserEntity();

        $user->id = Uuid::fromString($userFixture->user1Id);
        $user->userName = 'tes';
        $user->email = 'tes@gmail.com';
        $user->phoneNumber = '+6282246920112';
        $user->password = 'user123';

        $repository->create($user);
    }

    /**
     * The update positive scenario test: updated.
     * 
     * @return void
     */
    public function testUpdate_updated(): void
    {
        $this->expectNotToPerformAssertions();

        $repository = $this->container->get(UserRepositoryInterface::class);
        $userFixture = new UserFixture();

        $user = new UserEntity();

        $user->id = Uuid::fromString($userFixture->user1Id);
        $user->userName = 'tes1';
        $user->email = 'tes1@gmail.com';
        $user->phoneNumber = '+6282246920112';
        $user->password = 'user123';

        $repository->update($user);
    }

    /**
     * The update negative scenario test: id does not exist.
     * 
     * @return void
     */
    public function testUpdate_idDoesNotExist(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = $this->container->get(UserRepositoryInterface::class);

        $user = new UserEntity();

        $user->id = Uuid::fromString('00000000-0000-0000-0000-000000000000');
        $user->userName = 'tes1';
        $user->email = 'tes1@gmail.com';
        $user->phoneNumber = '+6282246920112';
        $user->password = 'user123';

        $repository->update($user);
    }

    /**
     * The delete positive scenario test: deleted.
     * 
     * @return void
     */
    public function testDelete_deleted(): void
    {
        $this->expectNotToPerformAssertions();

        $repository = $this->container->get(UserRepositoryInterface::class);
        $userFixture = new UserFixture();

        $user = new UserEntity();

        $user->id = Uuid::fromString($userFixture->user1Id);
        $user->userName = 'tes1';
        $user->email = 'tes1@gmail.com';
        $user->phoneNumber = '+6282246920112';
        $user->password = 'user123';

        $repository->delete($user);
    }

    /**
     * The delete negative scenario test: id does not exist.
     * 
     * @return void
     */
    public function testDelete_idDoesNotExist(): void
    {
        $this->expectNotToPerformAssertions();

        $repository = $this->container->get(UserRepositoryInterface::class);
        $user = new UserEntity();

        $user->id = Uuid::fromString('00000000-0000-0000-0000-000000000000');
        $user->userName = 'tes1';
        $user->email = 'tes1@gmail.com';
        $user->phoneNumber = '+6282246920112';
        $user->password = 'user123';

        $repository->delete($user);
    }
}
