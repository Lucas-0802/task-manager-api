<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;

class TaskControllerRefactor extends Controller
{
  public function __construct(protected TaskService $service) {}

  // MÉTODO LEGADO
  // public function store(Request $request)
  // {
  //   $task = new Task();
  //   $task->title = $request->title;
  //   $task->description = $request->description;
  //   $task->completed = false;
  //   $task->save();
  //   return response()->json($task);
  // }


  // MÉTODO REFATORADO

  public function store(TaskStoreRequest $request)
  {
    try {
      $task = $this->service->createTask($request->validated());
      return new TaskResource($task);
    } catch (\Throwable $th) {
      return response()->json(['error' => 'Failed to create task', 'message' => $th->getMessage()], 400);
    }
  }

    /*
    PONTOS QUE FORAM MELHORADOS:

    1. Validação da entrada dos dados:
     Antes o método store recebia diretamente a request, o que poderia ocasionar um erro inesperado
     quando fossem acessadas as propriedades com a '->' pois elas podem não existir, ou estar em 
     um formato inválido. Nesse arquivo de validação é possivel verificar os dados que estão vindo, 
     estabelecer regras de validação para eles, e também escrever mensagens personalizadas para cada tipo de erro, 
     além disso o método legado criava o recurso no banco de dados, o que deixava o código mais 
     acoplado e difícil de manter.

    2. Tratamento de erros:
      O método store agora está envolvido em um bloco try-catch, o que permite capturar qualquer
      exceção que possa ocorrer durante a criação da tarefa. Se ocorrer um erro, o catch irá 
      retornar uma resposta JSON com uma mensagem de erro e o status code 400, 
      indicando que houve um erro. Isso melhora a robustez da 
      aplicação e fornece feedback mais claro para o cliente em caso de falhas.

    3. Legibilidade e manutenção:
      O código refatorado é mais legível e fácil de entender, 
      pois a validação dos dados está claramente separada em uma classe de 
      request dedicada (TaskStoreRequest). 
      Isso torna o código mais organizado e facilita a manutenção futura, já que as
      regras de validação estão centralizadas em um único local. Além disso, o tratamento 
      de erros torna o código mais resiliente e fácil de depurar em caso de problemas.
      Além disso o resource TaskResource é utilizado para formatar a resposta de forma consistente, 
      o que melhora a experiência do cliente ao consumir a API.

    4. Princípio de responsabilidade única:
      A refatoração segue o princípio de responsabilidade única, onde cada classe tem 
      uma única responsabilidade. O TaskController agora é responsável apenas por lidar com as
      requisições e respostas, enquanto a lógica de validação e criação de tarefas é delegada para
      o TaskStoreRequest e TaskService, respectivamente. Isso torna o código mais modular e fácil
      de testar. Além disso o Service pode implementar o Repository Pattern para desacoplar ainda mais a 
      lógica de acesso aos dados, o que facilitaria a manutenção e a escalabilidade da aplicação.

    5. Retorno HTTP adequado:
      O método refatorado retorna uma resposta JSON com o recurso criado, o que é mais apropriado 
      para uma API. No caso de sucesso, ele retorna o recurso criado com status 201 (Created), e em 
      caso de falha, ele retorna uma mensagem de erro clara com status 400 (Bad Request).
      Isso melhora a experiência do cliente ao consumir a API e torna a comunicação mais eficaz.
      O status de erro também pode variar caso seja uma falha de validação (422), ou um conflito
      pois existe alguma regra de negócio que impede a criação da tarefa (409), um erro de recurso
      não encontrado (404), ou mesmo um erro de autenticação (401) ou outro tipo de erro, o erro 
      500 ficaria pra último caso, quando não ocorreu nenhum dos erros conhecidos.
      Os erros de validação são tratados automaticamente pelo Laravel, graças ao uso do FormRequest 
      (TaskStoreRequest), que retorna uma resposta JSON com os erros 
      de validação e o status code 422. Já os erros inesperados são capturados pelo bloco try-catch, 
      garantindo que a API responda de forma consistente mesmo em situações de falha.
    */
}
