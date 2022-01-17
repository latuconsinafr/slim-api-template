<?php

declare(strict_types=1);

namespace App\Tests\TestCases\Services\Users;

use App\Data\Entities\UserEntity;
use App\Data\TypeCasts\Uuid;
use App\Services\UserService;
use App\Tests\Fixtures\UserFixture;
use App\Tests\Traits\AppTestTrait;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * User Service Test.
 */
final class UserServiceTest extends TestCase
{
    use AppTestTrait;

    /**
     * The findAll test.
     * 
     * @return void
     */
    public function testFindAll(): void
    {
        $service = $this->container->get(UserService::class);
        $userFixture = new UserFixture();

        $usersSample = $userFixture->getUsers();
        $count = count((array)$usersSample);

        $users = $service->findAll();

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
     * The findById positive scenario test: found.
     * 
     * @return void
     */
    public function testFindById_found(): void
    {
        $service = $this->container->get(UserService::class);
        $userFixture = new UserFixture();

        $user = $service->findById(Uuid::fromString($userFixture->user1Id));

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
        $service = $this->container->get(UserService::class);

        $user = $service->findById(Uuid::fromString('00000000-0000-0000-0000-000000000000'));

        $this->assertEmpty($user);
        $this->assertNull($user);
        $this->assertNotInstanceOf(UserEntity::class, $user);
    }

    /**
     * The findByUserName positive scenario test: found.
     * 
     * @return void
     */
    public function testFindByUserName_found(): void
    {
        $service = $this->container->get(UserService::class);
        $userFixture = new UserFixture();

        $user = $service->findByUserName($userFixture->user1UserName);

        $this->assertNotEmpty($user);
        $this->assertNotNull($user);
        $this->assertInstanceOf(UserEntity::class, $user);
    }

    /**
     * The findByUserName negative scenario test: not found.
     * 
     * @return void
     */
    public function testFindByUserName_notFound(): void
    {
        $service = $this->container->get(UserService::class);
        $userFixture = new UserFixture();

        $user = $service->findByUserName($userFixture->user1PhoneNumber);

        $this->assertEmpty($user);
        $this->assertNull($user);
        $this->assertNotInstanceOf(UserEntity::class, $user);
    }

    /**
     * The findByEmail positive scenario test: found.
     * 
     * @return void
     */
    public function testFindByEmail_found(): void
    {
        $service = $this->container->get(UserService::class);
        $userFixture = new UserFixture();

        $user = $service->findByEmail($userFixture->user1Email);

        $this->assertNotEmpty($user);
        $this->assertNotNull($user);
        $this->assertInstanceOf(UserEntity::class, $user);
    }

    /**
     * The findByEmail negative scenario test: not found.
     * 
     * @return void
     */
    public function testFindByEmail_notFound(): void
    {
        $service = $this->container->get(UserService::class);
        $userFixture = new UserFixture();

        $user = $service->findByEmail($userFixture->user1UserName);

        $this->assertEmpty($user);
        $this->assertNull($user);
        $this->assertNotInstanceOf(UserEntity::class, $user);
    }

    /**
     * The findByPhoneNumber positive scenario test: found.
     * 
     * @return void
     */
    public function testFindByPhoneNumber_found(): void
    {
        $service = $this->container->get(UserService::class);
        $userFixture = new UserFixture();

        $user = $service->findByPhoneNumber($userFixture->user1PhoneNumber);

        $this->assertNotEmpty($user);
        $this->assertNotNull($user);
        $this->assertInstanceOf(UserEntity::class, $user);
    }

    /**
     * The findByPhoneNumber negative scenario test: not found.
     * 
     * @return void
     */
    public function testFindByPhoneNumber_notFound(): void
    {
        $service = $this->container->get(UserService::class);
        $userFixture = new UserFixture();

        $user = $service->findByPhoneNumber($userFixture->user1Email);

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
        $service = $this->container->get(UserService::class);
        $user = new UserEntity();

        $user->id = Uuid::create();
        $user->userName = 'tes';
        $user->email = 'tes@gmail.com';
        $user->phoneNumber = '+6282246920112';
        $user->password = 'user123';

        $result = $service->create($user);

        $this->assertNotEmpty($result);
        $this->assertNotNull($result);
        $this->assertInstanceOf(UserEntity::class, $result);
    }

    /**
     * The create negative scenario test: id does exist.
     * 
     * @return void
     */
    public function testCreate_idDoesExist(): void
    {
        $this->expectException(\Spiral\Database\Exception\StatementException\ConstrainException::class);

        $service = $this->container->get(UserService::class);
        $userFixture = new UserFixture();

        $user = new UserEntity();

        $user->id = Uuid::fromString($userFixture->user1Id);
        $user->userName = 'tes';
        $user->email = 'tes@gmail.com';
        $user->phoneNumber = '+6282246920112';
        $user->password = 'user123';

        $service->create($user);
    }

    /**
     * The update positive scenario test: updated.
     * 
     * @return void
     */
    public function testUpdate_updated(): void
    {
        $service = $this->container->get(UserService::class);
        $userFixture = new UserFixture();

        $user = new UserEntity();

        $user->id = Uuid::fromString($userFixture->user1Id);
        $user->userName = 'tes1';
        $user->email = 'tes1@gmail.com';
        $user->phoneNumber = '+6282246920112';
        $user->password = 'user123';

        $result = $service->update($user);

        $this->assertNotEmpty($result);
        $this->assertNotNull($result);
        $this->assertTrue($result);
    }

    /**
     * The update negative scenario test: id does not exist.
     * 
     * @return void
     */
    public function testUpdate_idDoesNotExist(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $service = $this->container->get(UserService::class);

        $user = new UserEntity();

        $user->id = Uuid::fromString('00000000-0000-0000-0000-000000000000');
        $user->userName = 'tes1';
        $user->email = 'tes1@gmail.com';
        $user->phoneNumber = '+6282246920112';
        $user->password = 'user123';

        $service->update($user);
    }

    /**
     * The delete positive scenario test: deleted.
     * 
     * @return void
     */
    public function testDelete_deleted(): void
    {
        $service = $this->container->get(UserService::class);
        $userFixture = new UserFixture();

        $result = $service->delete(Uuid::fromString($userFixture->user1Id));

        $this->assertNotEmpty($result);
        $this->assertNotNull($result);
        $this->assertTrue($result);
    }

    /**
     * The delete negative scenario test: id does not exist.
     * 
     * @return void
     */
    public function testDelete_idDoesNotExist(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $service = $this->container->get(UserService::class);

        $service->delete(Uuid::fromString('00000000-0000-0000-0000-000000000000'));
    }
}
