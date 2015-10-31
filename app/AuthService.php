<?php
use Klein\App;
use Klein\Request;

/**
 * @class AuthService
 */
class AuthService
{
    private $request;
    /** @var PDO */
    private $db;

    /**
     * AuthService constructor.
     *
     * @param Request $request
     * @param App $app
     */
    public function __construct(Request $request, App $app)
    {
        $this->request = $request;
        $this->db = $app->db;
    }

    function loginValidator()
    {
        $statement = $this->db->prepare("
            SELECT
              username,
              password,
              role
            FROM
              user
            WHERE
              username = :username
        ");

        $statement->execute([
            ":username" => $this->request->param('username')
        ]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (
            $row['username'] == $this->request->param('username')
            && $row['password'] == $this->request->param('password')
        )
        {
            if (($this->request->param('administrator') == "on") && ($row['role'] == 0))
            {  // login as a site administrator
                return 'admin';
            }

            return 'report';
        }
        else
        {
            return false;
        }
    }
}