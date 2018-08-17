create table access_log
(
  keyid       int auto_increment
    primary key,
  sessionid   varchar(32)                             null
  comment 'PHPSESSID',
  ip          varchar(32)                             null
  comment '访问ip',
  url         text                                    null
  comment '访问地址',
  account_id  int                                     null
  comment '访问用户(如果已登录)',
  action      varchar(32)                             null
  comment '动作',
  create_time timestamp default '0000-00-00 00:00:00' not null
  comment '日志时间'
)
  comment '访问日志'
  engine = InnoDB
  charset = utf8;

create table account
(
  keyid           int auto_increment
    primary key,
  username        varchar(32)                             not null
  comment '用户名',
  password        varchar(32)                             not null
  comment '密码',
  nickname        varchar(32)                             null
  comment '昵称',
  auth_id         int                                     null
  comment '权限表id',
  last_session    varchar(32)                             null
  comment '最后登录的sessionid',
  last_activetime timestamp                               null
  comment '最后活跃时间',
  update_time     timestamp default CURRENT_TIMESTAMP     not null
  on update CURRENT_TIMESTAMP,
  create_time     timestamp default '0000-00-00 00:00:00' not null
  comment '注册时间',
  constraint username
  unique (username)
)
  comment '账户表'
  engine = InnoDB
  charset = utf8;

create table auth
(
  keyid        int auto_increment
  comment '主键'
    primary key,
  role_name    varchar(256)                            not null
  comment '角色名称',
  role_comment varchar(256)                            null
  comment '角色名称注释',
  del_talk     int default '0'                         null
  comment '删除说说',
  update_time  timestamp default CURRENT_TIMESTAMP     not null
  on update CURRENT_TIMESTAMP,
  create_time  timestamp default '0000-00-00 00:00:00' not null,
  constraint auth_role_name_uindex
  unique (role_name)
)
  comment '角色权限主表';

create procedure del_data(IN tablename VARCHAR(256), IN keyid INT(10))
  begin
    DECLARE delete_tablename varchar(256);
    set delete_tablename = concat(tablename, '_delete');
    start transaction;
    set @stmt := concat('insert into ', delete_tablename, ' select * from ', tablename, ' where ', tablename,
                        '.keyid = ', keyid, ';');
    prepare stmt from @stmt;
    execute stmt;
    commit;
  end;

create function get_now_timestamp()
  returns timestamp
  return now();

create procedure ins_account(IN  username  VARCHAR(32), IN password VARCHAR(32), IN nickname VARCHAR(32),
                             IN  auth_id   INT(10), IN last_session VARCHAR(32), OUT keyid INT(10), OUT error INT(10),
                             OUT error_msg VARCHAR(256))
  lable: begin
    declare has_auth int(11);
    set error = 0;
    set error_msg = null;
    /*开始事务*/
    start transaction;
    if auth_id is not null /*判断角色是否存在*/
    then /*查询角色是否存在*/
      select auth.keyid from auth where auth.keyid = auth_id
          into has_auth;
      if has_auth is null
      then
        set error = 1;
        set error_msg = 'auth_id值错误';
        leave lable;
      end if;
    end if;
    /*执行insert语句*/
    insert account (username, password, nickname, auth_id, last_session)
    value (username, password, nickname, auth_id, last_session);
    if ROW_COUNT() > 0
    then
      set keyid = last_insert_id();
    else
      set error = 2;
      set error_msg = '未知错误';
    end if;
    commit;
  end;


