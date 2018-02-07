<?php

use yii\db\Migration;

class m170621_170516_init extends Migration
{
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $tableOptionsBin = 'CHARACTER SET utf8 COLLATE utf8_bin ENGINE=InnoDB';

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(16)->defaultValue(null),
            'password' => '/* yii2 fix */ '. $this->binary(16)->notNull(),
            'auth_key' => '/* yii2 fix */ '. $this->binary(16)->notNull(),
        ], $tableOptions . ' AUTO_INCREMENT=1000');
        $this->createIndex('idx-user-username', '{{%user}}', ['username'], true);

        $this->createTable('{{%city}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull(),
            'full_name' => $this->string(200)->notNull(),
            'nearby' => $this->string(200)->defaultValue(null),
            'lat' => $this->decimal(9, 6)->notNull(),
            'lng' => $this->decimal(9, 6)->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx-city-name', '{{%city}}', ['name']);

        $this->createTable('{{%name}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptionsBin);
        $this->createIndex('idx-name-name', '{{%name}}', ['name'], true);

        $this->createTable('{{%tag}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptionsBin);
        $this->createIndex('idx-tag-name', '{{%tag}}', ['name'], true);

        $this->createTable('{{%profile}}', [
            'user_id' => $this->integer()->notNull(),
            'name_id' => $this->integer()->defaultValue(null),
            'city_id' => $this->integer()->defaultValue(null),
            'status' => "enum('active','blocked') NOT NULL",
            'gender' => "enum('m','f','mf','fm','mm','ff') NOT NULL",
            'virtreal' => "enum('both','virt','real') NOT NULL",
            'age' => 'tinyint UNSIGNED DEFAULT NULL',
            'age_from' => 'tinyint UNSIGNED DEFAULT NULL',
            'age_to' => 'tinyint UNSIGNED DEFAULT NULL',
            'growth' => 'tinyint UNSIGNED DEFAULT NULL',
            'weight' => 'tinyint UNSIGNED DEFAULT NULL',
            'constitution' => 'tinyint UNSIGNED DEFAULT NULL',
            'request_counter' => $this->integer()->notNull(),
            'dialog_counter' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'visit_at' => $this->integer()->notNull(),
        ], $tableOptionsBin);
        $this->addPrimaryKey('', '{{%profile}}', ['user_id']);
        $this->createIndex('idx-profile-city', '{{%profile}}', ['city_id']);
        $this->createIndex('idx-profile-visit', '{{%profile}}', ['visit_at']);

        $this->createTable('{{%online}}', [
            'user_id' => $this->integer()->notNull(),
            'name_id' => $this->integer()->defaultValue(null),
            'city_id' => $this->integer()->defaultValue(null),
            'gender' => "enum('m','f','mf','fm','mm','ff') NOT NULL",
            'virt' => "enum('y','n') NOT NULL",
            'real' => "enum('y','n') NOT NULL",
            'tags' => $this->binary(200)->defaultValue(null),
            'age' => 'tinyint DEFAULT NULL',
            'age_from' => 'tinyint DEFAULT NULL',
            'age_to' => 'tinyint DEFAULT NULL',
            'created_at' => $this->integer()->notNull(),
            'up_at' => $this->integer()->notNull(),
        ], $tableOptionsBin);
        $this->addPrimaryKey('', '{{%online}}', ['user_id']);
        $this->createIndex('idx-online-up-virt-gender', '{{%online}}', ['up_at', 'virt', 'gender']);
        $this->createIndex('idx-online-city-up-gender', '{{%online}}', ['city_id', 'up_at', 'gender']);

        $this->createTable('{{%request}}', [
            'user_id' => $this->integer()->notNull(),
            'second_user_id' => $this->integer()->notNull(),
            'message_id' => $this->integer()->notNull(),
            'up_at' => $this->integer()->notNull(),
        ], $tableOptionsBin);
        $this->createIndex('idx-request-user-second_user', '{{%request}}', ['user_id', 'second_user_id'], true);
        $this->createIndex('idx-request-user-up', '{{%request}}', ['user_id', 'up_at']);

        $this->createTable('{{%dialog}}', [
            'user_id' => $this->integer()->notNull(),
            'second_user_id' => $this->integer()->notNull(),
            'message_id' => $this->integer()->defaultValue(null),
            'type' => "enum('sys','in','out','hidden','ban') NOT NULL",
            'unread_count' => 'tinyint NOT NULL',
            'private_comment' => $this->string(250)->defaultValue(null),
            'created_at' => $this->integer()->notNull(),
            'up_at' => $this->integer()->notNull(),
        ], $tableOptionsBin);
        $this->createIndex('idx-dialog-user-second_user', '{{%dialog}}', ['user_id', 'second_user_id'], true);
        $this->createIndex('idx-dialog-user-up', '{{%dialog}}', ['user_id', 'up_at']);

        $this->createTable('{{%message}}', [
            'id' => $this->primaryKey(),
            'up_user_id' => $this->integer()->notNull(),
            'down_user_id' => $this->integer()->notNull(),
            'direct' => "enum('up','down') NOT NULL",
            'read' => "enum('y','n') NOT NULL",
            'delete' => "enum('no','up', 'down','both') NOT NULL",
            'text' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptionsBin);
        $this->createIndex('idx-message-up-user-down-user', '{{%message}}', ['up_user_id', 'down_user_id']);

        $this->createTable('{{%auth_log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->defaultValue(null),
            'ip' => $this->string(100)->notNull(),
            'login' => $this->string(100)->notNull(),
            'password' => $this->string(100)->notNull(),
            'status' => "enum('success','blocked','login','password','denied','register') NOT NULL",
            'created_at' => $this->integer()->notNull(),
        ], $tableOptionsBin);
        $this->createIndex('idx-auth_log-user', '{{%auth_log}}', ['user_id']);
        $this->createIndex('idx-auth_log-ip', '{{%auth_log}}', ['ip']);

        $this->createTable('{{%profile_log}}', [
            'user_id' => $this->integer()->notNull(),
            'data' => $this->string(250)->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptionsBin);
        $this->createIndex('idx-profile_log-user-created', '{{%auth_log}}', ['user_id', 'created_at']);

        $this->addForeignKey('fk-profile-user', '{{%profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-profile-name', '{{%profile}}', 'name_id', '{{%name}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-profile-city', '{{%profile}}', 'city_id', '{{%city}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-online-name', '{{%online}}', 'name_id', '{{%name}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-online-city', '{{%online}}', 'city_id', '{{%city}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-request-user', '{{%request}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-dialog-user', '{{%dialog}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-online-user', '{{%online}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-auth_log-user', '{{%auth_log}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-profile_log-user', '{{%profile_log}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-profile_log-user', '{{%profile_log}}');
        $this->dropForeignKey('fk-auth_log-user', '{{%auth_log}}');
        $this->dropForeignKey('fk-online-user', '{{%online}}');
        $this->dropForeignKey('fk-dialog-user', '{{%dialog}}');
        $this->dropForeignKey('fk-request-user', '{{%request}}');
        $this->dropForeignKey('fk-online-city', '{{%online}}');
        $this->dropForeignKey('fk-online-name', '{{%online}}');
        $this->dropForeignKey('fk-profile-city', '{{%profile}}');
        $this->dropForeignKey('fk-profile-name', '{{%profile}}');
        $this->dropForeignKey('fk-profile-user', '{{%profile}}');

        $this->dropTable('{{%profile_log}}');
        $this->dropTable('{{%auth_log}}');
        $this->dropTable('{{%message}}');
        $this->dropTable('{{%dialog}}');
        $this->dropTable('{{%request}}');
        $this->dropTable('{{%online}}');
        $this->dropTable('{{%profile}}');
        $this->dropTable('{{%tag}}');
        $this->dropTable('{{%name}}');
        $this->dropTable('{{%city}}');
        $this->dropTable('{{%user}}');
    }
}
