<?php
/*
 * Formdin Framework
 * Copyright (C) 2012 Ministério do Planejamento
 * Criado por Luís Eugênio Barbosa
 * Essa versão é um Fork https://github.com/bjverde/formDin
 *
 * ----------------------------------------------------------------------------
 * This file is part of Formdin Framework.
 *
 * Formdin Framework is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public License version 3
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License version 3
 * along with this program; if not,  see <http://www.gnu.org/licenses/>
 * or write to the Free Software Foundation, Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA  02110-1301, USA.
 * ----------------------------------------------------------------------------
 * Este arquivo é parte do Framework Formdin.
 *
 * O Framework Formdin é um software livre; você pode redistribuí-lo e/ou
 * modificá-lo dentro dos termos da GNU LGPL versão 3 como publicada pela Fundação
 * do Software Livre (FSF).
 *
 * Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA
 * GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou
 * APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/LGPL em português
 * para maiores detalhes.
 *
 * Você deve ter recebido uma cópia da GNU LGPL versão 3, sob o título
 * "LICENCA.txt", junto com esse programa. Se não, acesse <http://www.gnu.org/licenses/>
 * ou escreva para a Fundação do Software Livre (FSF) Inc.,
 * 51 Franklin St, Fifth Floor, Boston, MA 02111-1301, USA.
 */

require_once '../classes/Ajuda.class.php';

class AjudaTest extends PHPUnit_Framework_TestCase {
	
	/**
	 *
	 * @var Ajuda
	 */
	private $ajuda;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		$this->ajuda = new Ajuda('Nome','Texto');
		$this->ajuda->setValorChave('valeuKey');
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->ajuda = null;
		parent::tearDown ();
	}
	
	public function testSetCorFundo_valor() {
		$esperado = '#EEFFE4';
		$this->ajuda->setCorFundo('#EEFFE4');
		$retorno = $this->ajuda->getCorFundo();
		
		$this->assertEquals($esperado, $retorno);
	}
	
	public function testSetCorFundo_null() {
		$esperado = '#FFFFE4';
		$retorno = $this->ajuda->getCorFundo();
		
		$this->assertEquals($esperado, $retorno);
	}
	
	/**
	 * Tests Ajuda->setCorFonte()
	 */
	public function testSetCorFonte() {
		$esperado = '#EEFFE4';
		$this->ajuda->setCorFonte('#EEFFE4');
		$retorno = $this->ajuda->getCorFonte();
		
		$this->assertEquals($esperado, $retorno);
	}
	
	public function testSetCorFonte_null() {
		$esperado = '#0000FF';
		$retorno = $this->ajuda->getCorFonte();
		
		$this->assertEquals($esperado, $retorno);
	}
}

