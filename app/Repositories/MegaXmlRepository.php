<?php

namespace App\Repositories;

use App\Models\Pagamento;
use DOMDocument;

class MegaXmlRepository
{

    public function montaXMLNotaMaterial($dataGeral)
    {
        /* create a dom document with encoding utf8 */
        $domtree = new DOMDocument('1.0', 'UTF-8');
        $domtree->formatOutput = true;
        /* create the root element of the xml tree */
        $xmlRoot = $domtree->createElement("Recebimento");

        //"I/U/D"
        $xmlRoot->setAttribute("OPERACAO", $dataGeral['OPERACAO']);
        $xmlRoot = $domtree->appendChild($xmlRoot);

        //Campo inserido manualmente no cadastro de obra do sys "Código da Filial"
        $node = $domtree->createElement("FIL_IN_CODIGO", $dataGeral['FIL_IN_CODIGO']);
        $xmlRoot->appendChild($node);

        //891 (Fernanda irá Criar mais dois códigos) "Código da Ação"
        $node = $domtree->createElement("ACAO_IN_CODIGO", $dataGeral['ACAO_IN_CODIGO']);
        $xmlRoot->appendChild($node);

        //(buscar de tabela do Mega (será enviada consulta por email)) "Tipo Documento Financeiro"
        $node = $domtree->createElement("CPAG_TPD_ST_CODIGO", $dataGeral['CPAG_TPD_ST_CODIGO']);
        $xmlRoot->appendChild($node);

        //Código Fornecedor dentro do SYS "Código do Agente."
        $node = $domtree->createElement("AGN_IN_CODIGO", $dataGeral['AGN_IN_CODIGO']);
        $xmlRoot->appendChild($node);

        //apenas texto "COD" "Identificador Agente"
        $node = $domtree->createElement("AGN_TAU_ST_CODIGO", $dataGeral['AGN_TAU_ST_CODIGO']);
        $xmlRoot->appendChild($node);

        //número SEFAZ "Nr.Nota Fiscal"
        $node = $domtree->createElement("RCB_ST_NOTA", $dataGeral['RCB_ST_NOTA']);
        $xmlRoot->appendChild($node);

        //SEFAZ "Informe o Código da série/subsérie de documento contábil/fiscal"
        $node = $domtree->createElement("SER_ST_CODIGO", $dataGeral['SER_ST_CODIGO']);
        $xmlRoot->appendChild($node);

        //Será enviado por e-mail "Tipo Documento"
        $node = $domtree->createElement("TDF_ST_SIGLA", $dataGeral['TDF_ST_SIGLA']);
        $xmlRoot->appendChild($node);

        //Data emissão NF "Data do documento fiscal"
        $node = $domtree->createElement("RCB_DT_DOCUMENTO", $dataGeral['RCB_DT_DOCUMENTO']);
        $xmlRoot->appendChild($node);

        //Data entrada "Data do Movimento"
        $node = $domtree->createElement("RCB_DT_MOVIMENTO", $dataGeral['RCB_DT_MOVIMENTO']);
        $xmlRoot->appendChild($node);

        //CIF = frete e o seguro são pagos pelo fornecedor
        //FOB = o comprador assume todos os riscos e custos
        $node = $domtree->createElement("TPR_ST_TIPOPRECO", $dataGeral['TPR_ST_TIPOPRECO']);// "Informe o Tipo de Preço"
        $xmlRoot->appendChild($node);

        //Buscar na tabela do Mega e salvar no contrato, também na entrada da NF pode-se escolher a forma de pagamento.
        $node = $domtree->createElement("COND_ST_CODIGO", $dataGeral['COND_ST_CODIGO']);// "Código da condição de pagamento."
        $xmlRoot->appendChild($node);

        //Criar campo Código centro de Custo na Obra (atual é 480) * REQUERIDO
        $node = $domtree->createElement("CCF_IN_REDUZIDO", $dataGeral['CCF_IN_REDUZIDO']);// "C. Custo Padrao"
        $xmlRoot->appendChild($node);

        //Criar campo no cadastro da obra (Código Projeto Padrão) * REQUERIDO
        $node = $domtree->createElement("PROJ_IN_REDUZIDO", $dataGeral['PROJ_IN_REDUZIDO']);// "Proj. Padrão"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_VALDESCGERAL", $dataGeral['RCB_RE_VALDESCGERAL']);// "Valor do Desconto Geral Nota Fiscal"
        $xmlRoot->appendChild($node);
        //nulo
        $node = $domtree->createElement("RCB_RE_VALACREGERAL", $dataGeral['RCB_RE_VALACREGERAL']);// "Valor do Acrescimo Geral"
        $xmlRoot->appendChild($node);
        //nulo(se vier uma NF com valores neste campo não aceitar)
        $node = $domtree->createElement("RCB_RE_VALDESCONTOS", $dataGeral['RCB_RE_VALDESCONTOS']);// "Valor total dos Descontos ( Por Item )"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_TOTALFRETE", $dataGeral['RCB_RE_TOTALFRETE']);// "Valor total do Frete"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_TOTALSEGURO", $dataGeral['RCB_RE_TOTALSEGURO']);// "Valor total do Seguro"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_TOTALDESPACESS", $dataGeral['RCB_RE_TOTALDESPACESS']);// "Valor Total despesas Acessórias"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_TOTALNOTA", $dataGeral['RCB_RE_TOTALNOTA']);// "Valor Total da Nota Fiscal"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_VLMERCADORIA", $dataGeral['RCB_RE_VLMERCADORIA']);// "Total de Mercadorias"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_VLICMS", $dataGeral['RCB_RE_VLICMS']);// "Valor Total do ICMS"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_VLICMSRETIDO", $dataGeral['RCB_RE_VLICMSRETIDO']);// "Valor total do ICMS Retido"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_VLIPI", $dataGeral['RCB_RE_VLIPI']);// "Valor Total do IPI"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_TOTALMAOOBRA", $dataGeral['RCB_RE_TOTALMAOOBRA']);// "Valor do Total de Mão de Obra"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_BASEICMS", $dataGeral['RCB_RE_BASEICMS']);// "Valor Total do ICMS"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_TOTALISS", $dataGeral['RCB_RE_TOTALISS']);// "Valor Total ISS"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_TOTALIRRF", $dataGeral['RCB_RE_TOTALIRRF']);// "Valor Total IRRF"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_TOTALIMPORTACAO", $dataGeral['RCB_RE_TOTALIMPORTACAO']);// "Valor de Despesas de Importacao"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_DESPNAOTRIB", $dataGeral['RCB_RE_DESPNAOTRIB']);// "Valor de Despesas não Tributadas"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_BASESUBTRIB", $dataGeral['RCB_RE_BASESUBTRIB']);// "Base de Calc. ICMS Retido"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_TOTALINSS", $dataGeral['RCB_RE_TOTALINSS']);// "Valor Total INSS"
        $xmlRoot->appendChild($node);
        //Nulo
        $node = $domtree->createElement("RCB_CL_OBSTRF", $dataGeral['RCB_CL_OBSTRF']);// "Observação Tributos"
        $xmlRoot->appendChild($node);
        //Colocar apenas info "Importada SYS Engenharia"
        $node = $domtree->createElement("RCB_CL_INFADIC", $dataGeral['RCB_CL_INFADIC']);// "Informações adicionais"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_ST_OBSFIN", $dataGeral['RCB_ST_OBSFIN']);// "Observação Financeiro"
        $xmlRoot->appendChild($node);
        //Nulo
        $node = $domtree->createElement("RCB_RE_SESTSENAT", $dataGeral['RCB_RE_SESTSENAT']);// "Valor Sest/Senat"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_VLPIS", $dataGeral['RCB_RE_VLPIS']);// "Valor PIS"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_VLCOFINS", $dataGeral['RCB_RE_VLCOFINS']);// "Vl. Cofins"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_TOTALCSLL", $dataGeral['RCB_RE_TOTALCSLL']);// "Valor CSLLporte"
        $xmlRoot->appendChild($node);

        //DEFAULT "RO" - TIPO DE TRANSPORTE
        $node = $domtree->createElement("RCB_CH_TIPOTRANS", $dataGeral['RCB_CH_TIPOTRANS']);// "RCB_CH_TIPOTRANS"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_ST_PLACA1", $dataGeral['RCB_ST_PLACA1']);// "Nr Placa 01"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_ST_PLACA2", $dataGeral['RCB_ST_PLACA2']);// "Nr Placa 02"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_ST_PLACA3", $dataGeral['RCB_ST_PLACA3']);// "Nr Placa 03"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_VLDESADUANEIRA", $dataGeral['RCB_RE_VLDESADUANEIRA']);// "Valor Desp Aduaneiras"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_OUTRASDESPIMP", $dataGeral['RCB_RE_OUTRASDESPIMP']);// "Outras Desp de Importação"
        $xmlRoot->appendChild($node);

        //Verificar com Fernanda/Lucas
        $node = $domtree->createElement("DRF_ST_CODIGOIR", $dataGeral['DRF_ST_CODIGOIR']);// "Código IR"
        $xmlRoot->appendChild($node);

        //Verificar com Fernanda/Lucas
        $node = $domtree->createElement("RCB_BO_CALCULARVALORES", $dataGeral['RCB_BO_CALCULARVALORES']);// "Identifica se Calculamos ou não os valores de Totais, Tributos (S/N)"
        $xmlRoot->appendChild($node);
        //NF
        $node = $domtree->createElement("RCB_ST_CHAVEACESSO", $dataGeral['RCB_ST_CHAVEACESSO']);// "Chave de Acesso NF-e"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_ICMSSTRECUPERA", $dataGeral['RCB_RE_ICMSSTRECUPERA']);// "Valor do ICMS ST Recuperado"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_BASESUBTRIBANT", $dataGeral['RCB_RE_BASESUBTRIBANT']);// "Valor da base de cálculo do ICMS Retido Anteriormente"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_VLICMSRETIDOANT", $dataGeral['RCB_RE_VLICMSRETIDOANT']);// "Valor do ICMS Retido Anteriormente"
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_BASEFUNRURAL", $dataGeral['RCB_RE_BASEFUNRURAL']);// "Base de cálculo do FUNRURAL."
        $xmlRoot->appendChild($node);

        $node = $domtree->createElement("RCB_RE_VALORFUNRURAL", $dataGeral['RCB_RE_VALORFUNRURAL']);// "Valor do FUNRURAL."
        $xmlRoot->appendChild($node);

        foreach ($dataGeral['itens'] as $data) {

            $nodeItens = $domtree->createElement("ItensRecebimento");
            $nodeItens->setAttribute("OPERACAO", $data['ITENS_OPERACAO']);
            $itensNode = $xmlRoot->appendChild($nodeItens);

            //NUMERO SEQUENCIAL POR NF (1,2,3,4,5....)
            $node = $domtree->createElement("RCI_IN_SEQUENCIA", $data['ITENS_RCI_IN_SEQUENCIA']);// "Numero da Sequencia dos Itens da Nota"
            $itensNode->appendChild($node);

            //TXT "COD"	COD
            $node = $domtree->createElement("PRO_ST_ALTERNATIVO", $data['ITENS_PRO_ST_ALTERNATIVO']);// "Cód.Alternativo"
            $itensNode->appendChild($node);

            //Código do insumo do SYS	*
            $node = $domtree->createElement("PRO_IN_CODIGO", $data['ITENS_PRO_IN_CODIGO']);// "Cód.Item"
            $itensNode->appendChild($node);

            //Qtd da NF
            $node = $domtree->createElement("RCI_RE_QTDEACONVERTER", $data['ITENS_RCI_RE_QTDEACONVERTER']);// "Qtde.Recebimento"
            $itensNode->appendChild($node);

            //Unidade recebida	SUGIRO TESTAR SEM A TAG NO INÍCIO
            $node = $domtree->createElement("UNI_ST_UNIDADEFMT", $data['ITENS_UNI_ST_UNIDADEFMT']);// "Unid. Receb."
            $itensNode->appendChild($node);

            //valor total do produto (sem frete/IPI)
            $node = $domtree->createElement("RCI_RE_VLMERCADORIA", $data['ITENS_RCI_RE_VLMERCADORIA']);// "Vl. Mercadoria"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VLIPI", $data['ITENS_RCI_RE_VLIPI']);// "Valor I.P.I"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VLFRETE", $data['ITENS_RCI_RE_VLFRETE']);// "Valor Frete"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VLSEGURO", $data['ITENS_RCI_RE_VLSEGURO']);// "Valor Seguro"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VLDESPESA", $data['ITENS_RCI_RE_VLDESPESA']);// "Desp.Acessórias"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_PERCICM", $data['ITENS_RCI_RE_PERCICM']);// "% ICMS"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_PERCIPI", $data['ITENS_RCI_RE_PERCIPI']);// "% IPI"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VLMOBRAP", $data['ITENS_RCI_RE_VLMOBRAP']);// "Vl. Mão de Obra"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_PEDESC", $data['ITENS_RCI_RE_PEDESC']);// "% Descto."
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VLDESC", $data['ITENS_RCI_RE_VLDESC']);// "Valor Descto."
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VLDESCPROP", $data['ITENS_RCI_RE_VLDESCPROP']);// "Valor Descto.Nota"
            $itensNode->appendChild($node);
            //nulo
            $node = $domtree->createElement("RCI_RE_VLFINANCPROP", $data['ITENS_RCI_RE_VLFINANCPROP']);// "RCI_RE_VLFINANCPROP"
            $itensNode->appendChild($node);
            //nf
            $node = $domtree->createElement("RCI_RE_VLIMPORTACAO", $data['ITENS_RCI_RE_VLIMPORTACAO']);// "Valor Importação"
            $itensNode->appendChild($node);
            //nf
            $node = $domtree->createElement("RCI_RE_VLICMS", $data['ITENS_RCI_RE_VLICMS']);// "Vl. ICMS"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCB_ST_NOTA", $data['ITENS_RCB_ST_NOTA']);// "Nr. Nota Fiscal"
            $itensNode->appendChild($node);

            //Unidade no Mega (ex. KG,M2,M3, etc) Unidade do cadastro de insumo do SYS	*
            $node = $domtree->createElement("UNI_ST_UNIDADE", $data['ITENS_UNI_ST_UNIDADE']);// "Unidade"
            $itensNode->appendChild($node);

            //Testar passando nulo
            $node = $domtree->createElement("FMT_ST_CODIGO", $data['ITENS_FMT_ST_CODIGO']);// "Cód. Conversor unidades"
            $itensNode->appendChild($node);
            //121 para serviço,
            //101 para material,
            //111 Energia Elétrica,
            //992 Serviço de Comunicação, 993 Frete
            $node = $domtree->createElement("APL_IN_CODIGO", $data['ITENS_APL_IN_CODIGO']);// "Codigo da Aplicação"
            $itensNode->appendChild($node);
            // 150 para material p/ construção,
            // 151 serviços técnicos,
            // 174 gastos com canteiro de obra,
            // 152 Mão de Obra para levantamento da obra
            $node = $domtree->createElement("TPC_ST_CLASSE", $data['ITENS_TPC_ST_CLASSE']);// "Tipo de Classe"
            $itensNode->appendChild($node);
            // Código de Aplicação ->
            // CFOP  = 121 -> 1933, 101 -> 1949, 111 -> 1253, 992 -> 1353, 993 -> 1303
            $node = $domtree->createElement("CFOP_IN_CODIGO", $data['ITENS_CFOP_IN_CODIGO']);// "Código Reduzido CFOP"
            $itensNode->appendChild($node);
            // Código do serviço Item (Confirmar com Lucas)
            $node = $domtree->createElement("COS_IN_CODIGO", $data['ITENS_COS_IN_CODIGO']);// "Código de Serviço do Item"
            $itensNode->appendChild($node);
            // UF estado
            $node = $domtree->createElement("UF_LOC_ST_SIGLA", $data['ITENS_UF_LOC_ST_SIGLA']);// "Estado em que o Serviço foi Prestado"
            $itensNode->appendChild($node);
            // MEGA	- CÓDIGO DE MUNICIPIO PADRÃO QUE ESTA NO MEGA OU PADRÃO CORREIOS
            $node = $domtree->createElement("MUN_LOC_IN_CODIGO", $data['ITENS_MUN_LOC_IN_CODIGO']);// "Codigo do Municipio em que o Serviço foi Prestado"
            $itensNode->appendChild($node);
            //SUGIRO TESTAR SEM A TAG NO INÍCIO
            $node = $domtree->createElement("ALM_IN_CODIGO", $data['ITENS_ALM_IN_CODIGO']);// "Almoxarifado do Item"
            $itensNode->appendChild($node);
            //SUGIRO TESTAR SEM A TAG NO INÍCIO
            $node = $domtree->createElement("LOC_IN_CODIGO", $data['ITENS_LOC_IN_CODIGO']);// "Localização do Item"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VALORPVV", $data['ITENS_RCI_RE_VALORPVV']);// "Valor PVV (Substituição ICMS)"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VLICMRETIDO", $data['ITENS_RCI_RE_VLICMRETIDO']);// "Vl ICMS Retido (Substituição)"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VLISENIPI", $data['ITENS_RCI_RE_VLISENIPI']);// "Valor do Isento IPI"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_IPIRECUPERA", $data['ITENS_RCI_RE_IPIRECUPERA']);// "Vl Recuperado IPI"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VLOUTRIPI", $data['ITENS_RCI_RE_VLOUTRIPI']);// "Vl. Outros IPI"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VLBASEIPI", $data['ITENS_RCI_RE_VLBASEIPI']);// "Base de Cálculo IPI"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_ICMSRECUPERA", $data['ITENS_RCI_RE_ICMSRECUPERA']);// "Vl Recuperado ICMS"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VLISENICM", $data['ITENS_RCI_RE_VLISENICM']);// "Valor do Isento de ICMS"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VLOUTRICM", $data['ITENS_RCI_RE_VLOUTRICM']);// "Valor Outros ICMS"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VLBASEICM", $data['ITENS_RCI_RE_VLBASEICM']);// "Base de Cálculo ICMS"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VALDIFICMS", $data['ITENS_RCI_RE_VALDIFICMS']);// "Valor do Imposto (Diferencial de Aliq. ICMS)"
            $itensNode->appendChild($node);
            //NF
            $node = $domtree->createElement("RCI_RE_BASEISS", $data['ITENS_RCI_RE_BASEISS']);// "Vl Base de Calculo ISS"
            $itensNode->appendChild($node);
            //NF
            $node = $domtree->createElement("RCI_RE_PERISS", $data['ITENS_RCI_RE_PERISS']);// "% de ISS"
            $itensNode->appendChild($node);
            //NF
            $node = $domtree->createElement("RCI_RE_VLISS", $data['ITENS_RCI_RE_VLISS']);// "Valor do Imposto de ISS"
            $itensNode->appendChild($node);
            //NF
            $node = $domtree->createElement("RCI_RE_BASEINSS", $data['ITENS_RCI_RE_BASEINSS']);// "Base de Calculo INSS"
            $itensNode->appendChild($node);
            //NF
            $node = $domtree->createElement("RCI_RE_PERINSS", $data['ITENS_RCI_RE_PERINSS']);// "% de INSS"
            $itensNode->appendChild($node);
            //NF
            $node = $domtree->createElement("RCI_RE_VLINSS", $data['ITENS_RCI_RE_VLINSS']);// "Vl INSS"
            $itensNode->appendChild($node);
            //NF
            $node = $domtree->createElement("RCI_RE_BASEIRRF", $data['ITENS_RCI_RE_BASEIRRF']);// "Base de Cálculo IRRF"
            $itensNode->appendChild($node);
            //NF
            $node = $domtree->createElement("RCI_RE_PERIRRF", $data['ITENS_RCI_RE_PERIRRF']);// "% de IRRF"
            $itensNode->appendChild($node);
            //NF
            $node = $domtree->createElement("RCI_RE_VLIRRF", $data['ITENS_RCI_RE_VLIRRF']);// "Valor IRRF"
            $itensNode->appendChild($node);
            //NF
            $node = $domtree->createElement("RCI_RE_BASESUBTRIB", $data['ITENS_RCI_RE_BASESUBTRIB']);// "Vl. Base de Cálculo Substituição"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_PERDIFICMS", $data['ITENS_RCI_RE_PERDIFICMS']);// "% de Aliquota Interna (Diferencial de Aliq. ICMS)"
            $itensNode->appendChild($node);
            //Verifica os 4 primeiros dígitos q vem na NF e faz comparação
            $node = $domtree->createElement("RCI_ST_NCM_EXTENSO", $data['ITENS_RCI_ST_NCM_EXTENSO']);// "Codigo Extenso do NCM"
            $itensNode->appendChild($node);
            //Não tem na BILD
            $node = $domtree->createElement("RCI_CH_STICMS_A", $data['ITENS_RCI_CH_STICMS_A']);// "Sit. Trib - ICMS"
            $itensNode->appendChild($node);
            //Não tem na BILD
            $node = $domtree->createElement("RCI_CH_STICMS_B", $data['ITENS_RCI_CH_STICMS_B']);// "Sit. Trib - ICMS"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VLDESPNAOTRIB", $data['ITENS_RCI_RE_VLDESPNAOTRIB']);// "Valor Despesa não trib."
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VALORMOEDA", $data['ITENS_RCI_RE_VALORMOEDA']);// "Vl. Converter"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VLICMRETIDOANT", $data['ITENS_RCI_RE_VLICMRETIDOANT']);// "Vl ICMS Retido Inform.(Substituição)"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_BASESUBTRIBANT", $data['ITENS_RCI_RE_BASESUBTRIBANT']);// "Vl Base de Calculo Inform(Substituição)"
            $itensNode->appendChild($node);
            //No valor X o %
            $node = $domtree->createElement("RCI_RE_VLPISRETIDO", $data['ITENS_RCI_RE_VLPISRETIDO']);// "Valor PIS Retido"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VLPISRECUPERA", $data['ITENS_RCI_RE_VLPISRECUPERA']);// "Valor PIS Recuperado"
            $itensNode->appendChild($node);
            //Busca o que está no código de Serviço X cadastro do Fornecedor
            $node = $domtree->createElement("RCI_RE_PERCPIS", $data['ITENS_RCI_RE_PERCPIS']);// "% de PIS"
            $itensNode->appendChild($node);
            //NF
            $node = $domtree->createElement("RCI_RE_VLPIS", $data['ITENS_RCI_RE_VLPIS']);// "Valor de Imposto PIS"
            $itensNode->appendChild($node);
            //Valor do serviço
            $node = $domtree->createElement("RCI_RE_VLBASEPIS", $data['ITENS_RCI_RE_VLBASEPIS']);// "Base de Cálculo PIS"
            $itensNode->appendChild($node);
            //No valor do serviço X o %
            $node = $domtree->createElement("RCI_RE_VLCOFINSRETIDO", $data['ITENS_RCI_RE_VLCOFINSRETIDO']);// "Valor COFINS Retido"
            $itensNode->appendChild($node);

            $node = $domtree->createElement("RCI_RE_VLCOFINSRECUPERA", $data['ITENS_RCI_RE_VLCOFINSRECUPERA']);// "Valor COFINS Recuperado"
            $itensNode->appendChild($node);
            //Busca o que está no código de Serviço X cadastro do Fornecedor
            $node = $domtree->createElement("RCI_RE_PERCCOFINS", $data['ITENS_RCI_RE_PERCCOFINS']);// "% de COFINS"
            $itensNode->appendChild($node);
            //No valor do serviço X o %
            $node = $domtree->createElement("RCI_RE_VLCOFINS", $data['ITENS_RCI_RE_VLCOFINS']);// "Valor de Imposto COFINS"
            $itensNode->appendChild($node);
            //Valor do serviço
            $node = $domtree->createElement("RCI_RE_VLBASECOFINS", $data['ITENS_RCI_RE_VLBASECOFINS']);// "Base de Cálculo COFINS"
            $itensNode->appendChild($node);
            //Busca o que está no código de Serviço X cadastro do Fornecedor
            $node = $domtree->createElement("RCI_RE_PERCSLL", $data['ITENS_RCI_RE_PERCSLL']);// "% de CSLL"
            $itensNode->appendChild($node);
            //Valor do serviço
            $node = $domtree->createElement("RCI_RE_VLBASECSLL", $data['ITENS_RCI_RE_VLBASECSLL']);// "Base de Cálculo CSLL"
            $itensNode->appendChild($node);
            //No valor do serviço X o %
            $node = $domtree->createElement("RCI_RE_VLCSLL", $data['ITENS_RCI_RE_VLCSLL']);// "Valor do Imposto CSLL"
            $itensNode->appendChild($node);
            $node = $domtree->createElement("NAT_ST_CODIGO", $data['ITENS_NAT_ST_CODIGO']);// "Natureza de Estoque"
            $itensNode->appendChild($node);
            $node = $domtree->createElement("RCI_RE_VLICMSDIFERIDO", $data['ITENS_RCI_RE_VLICMSDIFERIDO']);// "Vl do Imposto ICMS Diferido"
            $itensNode->appendChild($node);
            $node = $domtree->createElement("RCI_RE_VLDESADUANEIRA", $data['ITENS_RCI_RE_VLDESADUANEIRA']);// "Valor Desp Aduaneiras"
            $itensNode->appendChild($node);
            $node = $domtree->createElement("RCI_RE_OUTRASDESPIMP", $data['ITENS_RCI_RE_OUTRASDESPIMP']);// "Outras Desp de Importação"
            $itensNode->appendChild($node);
            $node = $domtree->createElement("RCI_ST_REFERENCIA", $data['ITENS_RCI_ST_REFERENCIA']);// "Referência"
            $itensNode->appendChild($node);
            $node = $domtree->createElement("RCI_BO_GENERICO", $data['ITENS_RCI_BO_GENERICO']);// "Item Genérico (S/N)"
            $itensNode->appendChild($node);
            $node = $domtree->createElement("COMPL_ST_DESCRICAO", $data['ITENS_COMPL_ST_DESCRICAO']);// "Descrição Item Genérico"
            $itensNode->appendChild($node);
            //Nulo (validar)
            $node = $domtree->createElement("COSM_IN_CODIGO", $data['ITENS_COSM_IN_CODIGO']);// "Tipo de Serviço"
            $itensNode->appendChild($node);
            //Busca o valor que está no cadastro do insumo no sys
            $node = $domtree->createElement("NCM_IN_CODIGO", $data['ITENS_NCM_IN_CODIGO']);// "Código Reduzido NCM"
            $itensNode->appendChild($node);
            $node = $domtree->createElement("RCO_ST_COMPLEMENTO", $data['ITENS_RCO_ST_COMPLEMENTO']);// "Observação Item"
            $itensNode->appendChild($node);
            //Quando for NF de serviço é S do contrário N
            $node = $domtree->createElement("RCI_BO_CALCULARVALORES", $data['ITENS_RCI_BO_CALCULARVALORES']);// "Calcular Valores Tributos"
            $itensNode->appendChild($node);
            $node = $domtree->createElement("RCI_RE_ICMSSTRECUPERA", $data['ITENS_RCI_RE_ICMSSTRECUPERA']);// "Valor do ICMS ST Recuperado"
            $itensNode->appendChild($node);
            $node = $domtree->createElement("RCI_RE_BASEFUNRURAL", $data['ITENS_RCI_RE_BASEFUNRURAL']);// "Base de cálculo do FUNRURAL."
            $itensNode->appendChild($node);
            $node = $domtree->createElement("RCI_RE_ALIQFUNRURAL", $data['ITENS_RCI_RE_ALIQFUNRURAL']);// "Alíquota de cálculo do FUNRURAL."
            $itensNode->appendChild($node);
            $node = $domtree->createElement("RCI_RE_VALORFUNRURAL", $data['ITENS_RCI_RE_VALORFUNRURAL']);// "Valor do FUNRURAL"
            $itensNode->appendChild($node);
            //NF
            $node = $domtree->createElement("STS_ST_CSOSN", $data['ITENS_STS_ST_CSOSN']);// "Código de Situação da Operação no Simples Nacional."
            $itensNode->appendChild($node);
            $node = $domtree->createElement("RCI_ST_STIPI", $data['ITENS_RCI_ST_STIPI']);// "Situação Tributária do IPI"
            $itensNode->appendChild($node);
            $node = $domtree->createElement("STP_ST_CSTPIS", $data['ITENS_STP_ST_CSTPIS']);// "Código da Situação Tributária do PIS"
            $itensNode->appendChild($node);
            $node = $domtree->createElement("STC_ST_CSTCOFINS", $data['ITENS_STC_ST_CSTCOFINS']);// "Código da Situação Tributária do COFINS"
            $itensNode->appendChild($node);
            $node = $domtree->createElement("RCI_RE_VLBASESESTSENAT", $data['ITENS_RCI_RE_VLBASESESTSENAT']);// "RCI_RE_VLBASESESTSENAT"
            $itensNode->appendChild($node);
            $node = $domtree->createElement("RCI_RE_PERCSESTSENAT", $data['ITENS_RCI_RE_PERCSESTSENAT']);// "RCI_RE_PERCSESTSENAT"
            $itensNode->appendChild($node);
            $node = $domtree->createElement("RCI_RE_VLSESTSENAT", $data['ITENS_RCI_RE_VLSESTSENAT']);// "RCI_RE_VLSESTSENAT"
            $itensNode->appendChild($node);
            $node = $domtree->createElement("RCI_CH_DEFIPI", $data['ITENS_RCI_CH_DEFIPI']);// "Define como será calculado o IPI"
            $itensNode->appendChild($node);
            $node = $domtree->createElement("RCI_RE_PAUTAIPI", $data['ITENS_RCI_RE_PAUTAIPI']);// "Valor da Pauta do IPI"
            $itensNode->appendChild($node);
            $node = $domtree->createElement("ENI_ST_CODIGO", $data['ITENS_ENI_ST_CODIGO']);// "Cód.Enquadramento IPI"
            $itensNode->appendChild($node);

            /*
            $nodeLotes = $domtree->createElement("LotesVinculados");
            $nodeLotes->setAttribute("OPERACAO", $data['LOTES_OPERACAO']);
            $nodeLotes = $itensNode->appendChild($nodeLotes);

            $node = $domtree->createElement("MVS_ST_LOTEFORNE", $data['LOTES_MVS_ST_LOTEFORNE']);// "Nr. Lote"
            $nodeLotes->appendChild($node);
            $node = $domtree->createElement("MVT_DT_MOVIMENTO", $data['LOTES_MVT_DT_MOVIMENTO']);// "Dt. Movimento"
            $nodeLotes->appendChild($node);
            $node = $domtree->createElement("MVS_DT_VALIDADE", $data['LOTES_MVS_DT_VALIDADE']);// "Dt. Validade"
            $nodeLotes->appendChild($node);
            $node = $domtree->createElement("MVS_ST_REFERENCIA", $data['LOTES_MVS_ST_REFERENCIA']);// "Referência de Estoque"
            $nodeLotes->appendChild($node);
            $node = $domtree->createElement("ALM_IN_CODIGO", $data['LOTES_ALM_IN_CODIGO']);// "Almoxarifado"
            $nodeLotes->appendChild($node);
            $node = $domtree->createElement("LOC_IN_CODIGO", $data['LOTES_LOC_IN_CODIGO']);// "Localização"
            $nodeLotes->appendChild($node);
            $node = $domtree->createElement("NAT_ST_CODIGO", $data['LOTES_NAT_ST_CODIGO']);// "Cód.Natureza Estoque"
            $nodeLotes->appendChild($node);
            $node = $domtree->createElement("MVS_DT_ENTRADA", $data['LOTES_MVS_DT_ENTRADA']);// "Dt. Entrada"
            $nodeLotes->appendChild($node);
            $node = $domtree->createElement("LMS_RE_QUANTIDADE", $data['LOTES_LMS_RE_QUANTIDADE']);// "Quantidade"
            $nodeLotes->appendChild($node);
            $node = $domtree->createElement("RCI_IN_SEQUENCIA", $data['LOTES_RCI_IN_SEQUENCIA']);// "Seq. Item"
            $nodeLotes->appendChild($node);

            $nodeCentroDeCusto = $domtree->createElement("CentroCusto");
            $nodeCentroDeCusto->setAttribute("OPERACAO", $data['CENTROCUSTO_OPERACAO']);
            $nodeCentroDeCusto = $itensNode->appendChild($nodeCentroDeCusto);

            //Número NF
            $node = $domtree->createElement("RCB_ST_NOTA", $data['CENTROCUSTO_RCB_ST_NOTA']);// "Nr. Documento"
            $nodeCentroDeCusto->appendChild($node);
            $node = $domtree->createElement("RCI_IN_SEQUENCIA", $data['CENTROCUSTO_RCI_IN_SEQUENCIA']);// "Seq. do Item da Nota"
            $nodeCentroDeCusto->appendChild($node);
            //NUMERO SEQUENCIAL POR NF (1,2,3,4,5....)
            $node = $domtree->createElement("IRC_IN_SEQUENCIA", $data['CENTROCUSTO_IRC_IN_SEQUENCIA']);// "Numero da Sequencia do Centro de Custo"
            $nodeCentroDeCusto->appendChild($node);
            //Código cadastrado na obra	*
            $node = $domtree->createElement("CCF_IN_REDUZIDO", $data['CENTROCUSTO_CCF_IN_REDUZIDO']);// "C.Custo"
            $nodeCentroDeCusto->appendChild($node);
            // 150 para material p/ construção,
            // 151 serviços técnicos,
            // 174 gastos com canteiro de obra,
            // 152 Mão de Obra para levantamento da obra (olhar vinculação no insumo)
            $node = $domtree->createElement("TPC_ST_CLASSE", $data['CENTROCUSTO_TPC_ST_CLASSE']);// "Código do Tipo de Classe"
            $nodeCentroDeCusto->appendChild($node);
            //100
            $node = $domtree->createElement("IRC_RE_PERC", $data['CENTROCUSTO_IRC_RE_PERC']);// "Perc.Rateio"
            $nodeCentroDeCusto->appendChild($node);
            //Percentual anterior
            $node = $domtree->createElement("IRC_RE_VLPROP", $data['CENTROCUSTO_IRC_RE_VLPROP']);// "Vlr.Proporcional"
            $nodeCentroDeCusto->appendChild($node);

            $nodeProjetos = $domtree->createElement("Projetos");
            $nodeProjetos->setAttribute("OPERACAO", $data['PROJETOS_OPERACAO']);
            $nodeProjetos = $itensNode->appendChild($nodeProjetos);

            $node = $domtree->createElement("RCB_ST_NOTA", $data['PROJETOS_RCB_ST_NOTA']);// "Nr. Documento"
            $nodeProjetos->appendChild($node);
            $node = $domtree->createElement("RCI_IN_SEQUENCIA", $data['PROJETOS_RCI_IN_SEQUENCIA']);// "Seq. do Item da Nota"
            $nodeProjetos->appendChild($node);
            //Código Centro de Custo cadastrado na obra
            $node = $domtree->createElement("IRC_IN_SEQUENCIA", $data['PROJETOS_IRC_IN_SEQUENCIA']);// "Numero da Sequencia do Centro de Custo ao qual o Projeto esta amarrado"
            $nodeProjetos->appendChild($node);
            //NUMERO SEQUENCIAL POR NF (1,2,3,4,5....)
            $node = $domtree->createElement("IRP_IN_SEQUENCIA", $data['PROJETOS_IRP_IN_SEQUENCIA']);// "Sequencia Projeto"
            $nodeProjetos->appendChild($node);
            //Código cadastrado na obra	*
            $node = $domtree->createElement("PROJ_IN_REDUZIDO", $data['PROJETOS_PROJ_IN_REDUZIDO']);// "Projeto"
            $nodeProjetos->appendChild($node);
            //Código vinculado ao Insumo no Mega
            $node = $domtree->createElement("TPC_ST_CLASSE", $data['PROJETOS_TPC_ST_CLASSE']);// "Código do Tipo de Classe"
            $nodeProjetos->appendChild($node);
            //100
            $node = $domtree->createElement("IRP_RE_PERC", $data['PROJETOS_IRP_RE_PERC']);// "Perc.Rateio"
            $nodeProjetos->appendChild($node);
            //valor do percentual x valor do item
            $node = $domtree->createElement("IRP_RE_VLPROP", $data['PROJETOS_IRP_RE_VLPROP']);// "Vlr.Proporcional"
            $nodeProjetos->appendChild($node);

            $nodeParcelas = $domtree->createElement("Parcelas");
            $nodeParcelas->setAttribute("OPERACAO", $data['PARCELAS_OPERACAO']);
            $parcelasNode = $xmlRoot->appendChild($nodeParcelas);

            //NF'
            //NF'
            $node = $domtree->createElement("RCB_ST_NOTA", $data['PARCELAS_RCB_ST_NOTA']);// "Nr.Nota Fiscal"
            $parcelasNode->appendChild($node);

            //mesmo número da NF
            $node = $domtree->createElement("MOV_ST_DOCUMENTO", $data['PARCELAS_MOV_ST_DOCUMENTO']);// "Documento"
            $parcelasNode->appendChild($node);

            //NNN	NUMERO SEQUENCIAL POR NF (001,002,003,004,005....).
            $node = $domtree->createElement("MOV_ST_PARCELA", $data['PARCELAS_MOV_ST_PARCELA']);// "Parcela"
            $parcelasNode->appendChild($node);

            //DD/MM/YYYY	Considerar dias mínimos vencimento do Fornecedor (será buscado do Mega)
            $node = $domtree->createElement("MOV_DT_VENCTO", $data['PARCELAS_MOV_DT_VENCTO']);// "Vencimento"
            $parcelasNode->appendChild($node);

            $node = $domtree->createElement("MOV_RE_VALORMOE", $data['PARCELAS_MOV_RE_VALORMOE']);// "Valor Parcela"
            $parcelasNode->appendChild($node);
            */

        }

        /* get the xml printed */
        return $domtree->saveXML();
    }

    public function montaXMLPagamento(Pagamento $pagamento)
    {
        /* create a dom document with encoding utf8 */
        $domtree = new DOMDocument('1.0', 'UTF-8');
        $domtree->formatOutput = true;
        /* create the root element of the xml tree */
        $xmlRoot = $domtree->createElement("Fatura");

        //"I/U/D"
        $xmlRoot->setAttribute("OPERACAO", 'I');
        $xmlRoot = $domtree->appendChild($xmlRoot);

        // Origem do movimento = P para contas a pagar, R para contas a receber
        $node = $domtree->createElement("MOV_CH_ORIGEM", 'P');
        $xmlRoot->appendChild($node);

        //Código Fornecedor dentro do SYS "Código do Agente."
        $node = $domtree->createElement("AGN_ST_CODIGO", $pagamento->fornecedor->codigo_mega);
        $xmlRoot->appendChild($node);

        //apenas texto "COD" "Identificador Agente"
        $node = $domtree->createElement("AGN_ST_TIPOCODIGO", 'COD');
        $xmlRoot->appendChild($node);


        //TPD_ST_CODIGO Código do tipo de documento Financeiro.
        // O tipo de documento é um cadastro do Mega, para definições de tipos de documentos e suas respectivas
        // configurações utilizadas no módulo financeiro.
        $node = $domtree->createElement("TPD_ST_CODIGO", $pagamento->documentoTipo->codigo_mega);
        $xmlRoot->appendChild($node);

        // FAT_IN_NUMERO - Número do documento principal.
        $node = $domtree->createElement("FAT_IN_NUMERO", $pagamento->numero_documento);
        $xmlRoot->appendChild($node);

        // FIL_IN_CODIGO - Código da Filial
        $node = $domtree->createElement("FIL_IN_CODIGO", $pagamento->obra->filial_id);
        $xmlRoot->appendChild($node);

        // FAT_DT_EMISSAO = Data de Emissão da fatura
        $node = $domtree->createElement("FAT_DT_EMISSAO", $pagamento->data_emissao->format('d/m/Y'));
        $xmlRoot->appendChild($node);

        // FPA_DT_ENTRADA - Data de Entrada da Fatura - Não obrigatória
        $node = $domtree->createElement("FPA_DT_ENTRADA", $pagamento->created_at->format('d/m/Y'));
        $xmlRoot->appendChild($node);

        // FAT_RE_VALOR - Valor da Fatura
        $node = $domtree->createElement("FAT_RE_VALOR", $pagamento->valor);// "C. Custo Padrao"
        $xmlRoot->appendChild($node);

        // ACAO_IN_CODIGO - Código de Ação
        //  150 - adiantamento
        //  149 - documentos não fiscais (Recibo / Boleto sem NF / Conta de consumo....)
        //  891 -  usar para o restante
        $node = $domtree->createElement("ACAO_IN_CODIGO", '891');
        $xmlRoot->appendChild($node);

        // COND_ST_CODIGO - Código da Condição de Pagamento
        $node = $domtree->createElement("COND_ST_CODIGO", $pagamento->pagamentoCondicao->codigo);
        $xmlRoot->appendChild($node);

        if($pagamento->parcelas){
            $countParcela = 0;
            foreach ($pagamento->parcelas as $parcela) {
                $countParcela++;
                $nodeItens = $domtree->createElement("Parcela");
                $nodeItens->setAttribute("OPERACAO", 'I');
                $itensNode = $xmlRoot->appendChild($nodeItens);

                // MOV_ST_PARCELA - NUMERO SEQUENCIAL POR NF (1,2,3,4,5....)
                $node = $domtree->createElement("MOV_ST_PARCELA", $countParcela);// "Numero da Sequencia dos Itens da Nota"
                $itensNode->appendChild($node);

                // MOV_DT_VENCTO - data_vencimento
                $node = $domtree->createElement("MOV_DT_VENCTO", $parcela->data_vencimento->format('d/m/Y'));
                $itensNode->appendChild($node);

                //    numero_documento
                if($parcela->numero_documento){
                    $node = $domtree->createElement("MOV_ST_DOCUMENTO", $parcela->numero_documento);
                    $itensNode->appendChild($node);
                }

                //    percentual_juro_mora
                if($parcela->percentual_juro_mora > 0){
                    $node = $domtree->createElement("MOV_RE_PERCJUROSEFET", $parcela->percentual_juro_mora);
                    $itensNode->appendChild($node);
                }

                //    valor_juro_mora
                if($parcela->valor_juro_mora > 0){
                    $node = $domtree->createElement("MOV_RE_VRMORAEFET", $parcela->valor_juro_mora);
                    $itensNode->appendChild($node);
                }

                //    percentual_multa
                if($parcela->percentual_multa > 0){
                    $node = $domtree->createElement("MOV_RE_PERCMULTAEFET", $parcela->percentual_multa);
                    $itensNode->appendChild($node);
                }

                //    valor_multa
                if($parcela->valor_multa > 0){
                    $node = $domtree->createElement("MOV_RE_VRMULTAEFET", $parcela->valor_multa);
                    $itensNode->appendChild($node);
                }

                //    data_base_multa
                if($parcela->data_base_multa){
                    $node = $domtree->createElement("MOV_DT_DTBASEMULTA", $parcela->data_base_multa->format('d/m/Y'));
                    $itensNode->appendChild($node);
                }

                //    percentual_desconto
                if($parcela->percentual_desconto > 0){
                    $node = $domtree->createElement("MOV_RE_PERCDESCEFET", $parcela->percentual_desconto);
                    $itensNode->appendChild($node);
                }

                //    valor_desconto
                if($parcela->valor_desconto > 0){
                    $node = $domtree->createElement("MOV_RE_VRDESCCONDEFET", $parcela->valor_desconto);
                    $itensNode->appendChild($node);
                }
            }
        }

        /* get the xml printed */
        return $domtree->saveXML();
    }

}