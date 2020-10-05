<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class LocationControllerTest extends TestCase
{
    /**
     * @test
     * @testdox
     */
    public function caseOne()
    {
        $data = [
            "satelites" => [
                [
                    "name" => "kenobi",
                    "distance" => 5806600,
                    "message" => ["este", "", "", "mensaje", ""]
                ],
                [
                    "name" => "skywalker",
                    "distance" => 5806615.5,
                    "message" => ["", "es", "", "", "secreto"]
                ],
                [
                    "name" => "sato",
                    "distance" => 5806642.7,
                    "message" => ["este", "", "un", "", ""]
                ]
            ]
        ];

        $response = $this->postJson("/api/topsecret", $data);
        $response->assertSuccessful();
        $response->assertJson([
            "position" => [
                "X" => 9.612371971752903,
                "Y" => -2.8849892115312006
            ],
            "message" => "este es un mensaje secreto"
        ]);
    }

    /**
     * @test
     * @testdox
     */
    public function caseTwo()
    {
        $data = [
            "satelites" => [
                [
                    "name" => "kenobi",
                    "distance" => 1,
                    "message" => ["este", "", "", "mensaje", ""]
                ],
                [
                    "name" => "skywalker",
                    "distance" => 1,
                    "message" => ["", "es", "", "", "secreto"]
                ],
                [
                    "name" => "sato",
                    "distance" => 1,
                    "message" => ["este", "", "un", "", ""]
                ]
            ]
        ];

        $response = $this->postJson("/api/topsecret", $data);
        $response->assertStatus(404);
        $this->assertEquals("Position do not intersect.", $response->exception->getMessage());
    }

    /**
     * @test
     * @testdox
     */
    public function caseThree()
    {
        $data = [
            "satelites" => [
                [
                    "name" => "kenobi",
                    "distance" => 5806600,
                    "message" => ["este", "", "", "mensaje", ""]
                ],
                [
                    "name" => "skywalker",
                    "distance" => 5806615.5,
                    "message" => ["", "es", "", "", ""]
                ],
                [
                    "name" => "sato",
                    "distance" => 5806642.7,
                    "message" => ["este", "", "un", "", ""]
                ]
            ]
        ];

        $response = $this->postJson("/api/topsecret", $data);
        $response->assertStatus(404);
        $this->assertEquals("The message did not arrive complete.", $response->exception->getMessage());
    }

    /**
     * @test
     * @testdox
     */
    public function caseFour()
    {
        $data = [
            "distance" => 5806600,
            "message" => ["este", "", "", "mensaje", ""]
        ];

        $response = $this->postJson("/api/topsecret_split/kenobi", $data);
        $response->assertStatus(404);
        $this->assertEquals("The message did not arrive complete.", $response->exception->getMessage());
    }

    /**
     * @test
     * @testdox
     */
    public function caseFive()
    {
        $data = [
            "distance" => 5806600,
            "message" => ["este", "es", "un", "mensaje", "secreto"]
        ];

        $response = $this->postJson("/api/topsecret_split/kenobi", $data);
        $response->assertSuccessful();
        $response->assertJson([
            "position" => [
                "X" => -500,
                "Y" => -200
            ],
            "message" => "este es un mensaje secreto"
        ]);
    }
}