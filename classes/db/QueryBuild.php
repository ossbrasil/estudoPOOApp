<?php
include_once 'BindsTrait.php';
abstract class QueryBuild 
{
    use BindsTrait;
    protected $sql = '';
    protected $where = '';
    protected $order = '';
    protected $limit = null;
    protected $offset = null;
    protected $valores = [];

    protected $operador;
    protected $andWhere = [];
    protected $orWhere = [];
    protected $orders = [];
    
    public $registros;


    abstract public function select();
    abstract public function get();
    abstract public function first();
    abstract public function last();
    
    /**
     * ---------------------------------------------------------------------------------------------
     */
    public function or()
    {
        $this->verificarOperador();
        $this->montarWhere();
        $this->operador = 'OR';

        return $this;
    }

     /**
     * ---------------------------------------------------------------------------------------------
     */
    public function and()
    {
        $this->verificarOperador();
        $this->montarWhere();
        $this->operador = 'AND';

        return $this;

    }

    public function groupWhere()
    {
        if(!$this->where) {
            throw new \Exception('Não existe uma clasula where para ser agrupada');
        }

        if(!$this->operador) {
            throw new \Exeption('Execute o metodo or ou and antes do groupWhere');
        }

        $this->where = "({$this->where})";

        return $this;
    }

     /**
     * ---------------------------------------------------------------------------------------------
     */
    public function where(String $atributo, $valor, String $operador = '=')
    {
        $this->andWhere[] = [$atributo, $operador, $valor];
        return $this;   
    }

    /**
     * ---------------------------------------------------------------------------------------------
     */
    public function orWhere(String $atributo, $valor, String $operador = '=')
    {
        $this->orWhere[] = [$atributo, $operador, $valor];
        return $this;
    }

    /**
     * ---------------------------------------------------------------------------------------------
     */
    public function orWhereIn(String $atributo, Array $valores, String $operador = 'IN') 
    {
        $operador = strtoupper($operador);

        if(!in_array($operador, ['IN', 'NOT IN'])) {
            throw new \Exception('Operador inválido: os operadores permitidos para orWhereDentre são: IN e NOT IN');
        }

        $this->orWhere[] = [$atributo, $operador, $valores];

        return $this;
    }

    /**
     * ---------------------------------------------------------------------------------------------
     */
    public function whereIn(String $atributo, Array $valores, String $operador = 'IN') 
    {
        $operador = strtoupper($operador);
        if(!in_array($operador, ['IN', 'NOT IN'])) {
            throw new \Exception('Operador inválido: os operadores permitidos para orWhereDentre são: IN e NOT IN');
        }

        $this->andWhere[] = [$atributo, $operador, $valores];

        return $this;
    }

    /**
     * ---------------------------------------------------------------------------------------------
     */
    public function orWhereNull(String $atributo, String $operador = 'IS NULL')
    {
        $operador = strtoupper($operador);
        if(!in_array($operador, ['IS NULL', 'IS NOT NULL'])) {
            throw new \Exception('Operador inválido: os operadores permitidos para orWhereDentre são: IS e IS NOT');
        }

        $this->orWhere[] = [$atributo, $operador, ''];

        return $this;
    }

    /**
     * ---------------------------------------------------------------------------------------------
     */
    public function whereNull(String $atributo, String $operador = 'IS NULL')
    {
        $operador = strtoupper($operador);

        if(!in_array($operador, ['IS NULL', 'IS NOT NULL'])) {
            throw new \Exception('Operador inválido: os operadores permitidos para orWhereDentre são: IS e IS NOT');
        }

        $this->andWhere[] = [$atributo, $operador, ''];

        return $this;
    }

    /**
     * --------------------------------------------------------------------------------------------
     */
     public function whereBetween(String $atributo, Array $valores)
     {
        if(count($valores) != 2) {
            throw new \Exception('A operação BETWEEN deve ter somente dois valores no segundo atributo');
        }

        $this->andWhere[] = [$atributo, 'BETWEEN', $valores];

        return $this;

     }

     public function orWhereBetween(String $atributo, Array $valores)
     {
        if(count($valores) != 2) {
            throw new \Exception('A operação BETWEEN deve ter somente dois valores no segundo atributo');
        }
        
        $this->orWhere[] = [$atributo, 'BETWEEN', $valores];

        return $this;

     }

    /**
     * ---------------------------------------------------------------------------------------------
     */
    public function order(String $atributo, $operador = 'ASC') 
    {
        $operador = strtoupper($operador);

        if(!in_array($operador, ['ASC', 'DESC'])) {
            throw new \Exception('Operador de ordenção incorreto, apenas ASC e DESC são permitidos');
        }
        $this->orders[] = " {$atributo} {$operador}";

        $this->order = implode(' , ', $this->orders);
        
        return $this;
    }

    /**
     * ---------------------------------------------------------------------------------------------
     */
    public function limit(Int $quantity)
    {
        if($this->limit) {
            throw new \Exception('Atenção: Limit já está configurado');
        }

        $this->limit = $quantity;

        return $this;
    }

    /**
     * ---------------------------------------------------------------------------------------------
     */
    public function offset(Int $start)
    {
        if($this->offset) {
            throw new \Exception('Atenção: Offset já está configurado');
        }

        $this->offset = $start;

        return $this;
    }

    protected function constructSql() : void
    {
        $this->montarWhere();

        if($this->where) {
            $this->sql .= ' WHERE '. $this->where;
        }

        if ($this->order) {
            $this->sql .= " ORDER BY {$this->order}";
        }
        
        if ($this->limit) {
            $this->sql .= " LIMIT {$this->limit}";
        }

        if ($this->offset) {
            $this->sql .= " OFFSET {$this->offset}";
        }
    }

    /**
     * ---------------------------------------------------------------------------------------------
     */
    private function verificarOperador()
    {

        if (!count($this->andWhere) and !count($this->orWhere)) {
            throw new \Exception('Operação inválida, inclua uma operação where antes de incluir um oeprador');
        }
    }

    /**
     * ---------------------------------------------------------------------------------------------
     */
    private function montarWhere()
    {
        if(!$this->operador and $this->where != '' and (count($this->andWhere) or count($this->orWhere))) {
            throw new \Exception('Operação inválida, inclua um operador and|or utilizando os metodos and()|or()');
        }

        if($this->operador) { 
            $this->where .= ' '. $this->operador;
            $this->operador = null;
        }

        if(count($this->andWhere)) {
            $where = $this->whereAnd($this->andWhere);
            $this->where .= ' '. $where['condicao']; 
            $this->andWhere = [];
            $this->valores = array_merge($this->valores, $where['valores']);
        }

        if(count($this->orWhere)) {
            $where = $this->whereOr($this->orWhere);
            $this->where .= ' '. $where['condicao'];
            $this->orWhere = []; 
            $this->valores = array_merge($this->valores, $where['valores']);
        }
    }

    /**
     * --------------------------------------------------------------------------------------
     */
    protected function insert(Array $dados) : string
    {
        return implode(',', array_keys($dados));
    }

    /**
     * -----------------------------------------------------------------------------------
     */

     public function paged()
     {
        $query = preg_replace("/\n/", '', $this->sql);
        preg_match('/FROM.*/',$query, $matches);
        $queryFrom = $matches[0];
        preg_match('/(FROM *)([a-z_0-9]* *)([a-z_0-9]*)/', $queryFrom, $from);

        $entidade = array_filter($from);
        $atributo = 'count('.trim(end($entidade)).'.id) registros';
        
        $result = ConnectDB::query("SELECT $atributo $queryFrom");
        $this->registros = $result[0]->registros;
        
        return $this; 

     }
}